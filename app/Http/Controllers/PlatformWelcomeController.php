<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\SectionAccess;
use App\Services\AdaptiveExamService;
use App\Services\CategoryProficiencyService;
use App\Services\ExamCountdownService;
use App\Services\FocusCategoryService;
use App\Services\FocusExamService;
use App\Services\StripeCheckoutService;
use App\Services\StudyService;
use App\Support\PlatformExercise;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PlatformWelcomeController extends Controller
{
    public function __construct(
        private StripeCheckoutService $stripe,
        private AdaptiveExamService $examService,
        private CategoryProficiencyService $proficiency,
        private FocusCategoryService $focusCategory,
        private FocusExamService $focusExams,
        private StudyService $study,
        private ExamCountdownService $countdown,
    ) {}

    public function show(Request $request): View|RedirectResponse
    {
        $level = $request->attributes->get('certification_level');
        $slug = $request->attributes->get('section_slug');
        $user = $request->user();

        abort_unless($user !== null, 403);

        $this->fulfillCheckoutIfNeeded($request);

        if (! $user->fresh()->hasSectionAccess($level)) {
            return redirect()->route('platform.paywall', $slug);
        }

        $access = $this->examService->sectionAccess($user, $level);
        $categoryStats = $this->proficiency->forUser($user, $level);
        $overallStats = $this->proficiency->overall($user, $level);
        $weakCategories = $categoryStats->sortBy('accuracy_percent')->values();
        $pinnedFocus = $this->focusCategory->resolvePinned($request, $level, $weakCategories->take(3));
        $focusOptions = $this->focusExams->optionsForLevel(
            $level,
            $categoryStats,
            ($overallStats['total'] ?? 0) > 0 ? $overallStats['accuracy_percent'] : null,
        );

        $focusOption = $pinnedFocus !== null
            ? $focusOptions->first(fn ($option) => $option->focus_category === $pinnedFocus)
            : $focusOptions->first();

        $totalMissed = count($this->study->wrongQuestionIds($user, $level));
        $activeStudySession = $this->study->activeSession($user, $level);
        $activeExamSession = $user->activeExamSession($level);
        $exercises = PlatformExercise::cardsForLevel($level);

        $firstName = trim(explode(' ', $user->name)[0] ?? '');
        [$trackPurchaseConversion, $purchaseTransactionId] = $this->purchaseConversionContext($request, $user, $level);

        return view('platform.welcome', [
            'access' => $access,
            'examCountdown' => $this->countdown->forDate($access->exam_date),
            'firstName' => $firstName !== '' ? $firstName : null,
            'focusOption' => $focusOption,
            'pinnedFocus' => $pinnedFocus,
            'totalMissed' => $totalMissed,
            'activeStudySession' => $activeStudySession,
            'activeExamSession' => $activeExamSession,
            'exercises' => array_slice($exercises, 0, 4),
            'exerciseCount' => count($exercises),
            'hasExercises' => $exercises !== [],
            'trackPurchaseConversion' => $trackPurchaseConversion,
            'purchaseTransactionId' => $purchaseTransactionId,
        ]);
    }

    public function updateExamDate(Request $request): RedirectResponse
    {
        $level = $request->attributes->get('certification_level');
        $slug = $request->attributes->get('section_slug');
        $user = $request->user();

        abort_unless($user !== null, 403);
        abort_unless($user->hasSectionAccess($level), 403);

        $access = $this->examService->sectionAccess($user, $level);

        if ($request->input('exam_date') === '' || $request->input('exam_date') === null) {
            $access->exam_date = null;
            $access->save();

            return redirect()
                ->route('platform.welcome', $slug)
                ->with('success', 'Exam date cleared.');
        }

        $validated = $request->validate([
            'exam_date' => ['required', 'date', 'after_or_equal:today', 'before_or_equal:'.now()->addYears(2)->toDateString()],
        ]);

        $access->exam_date = $validated['exam_date'];
        $access->save();

        return redirect()
            ->route('platform.welcome', $slug)
            ->with('success', 'Exam date saved — your countdown is live in the header.');
    }

    private function fulfillCheckoutIfNeeded(Request $request): void
    {
        $sessionId = $request->query('session_id');

        if (is_string($sessionId) && $sessionId !== '') {
            $this->stripe->fulfillFromCheckoutSessionId($sessionId);

            return;
        }

        $paymentIntentId = $request->query('payment_intent');

        if (is_string($paymentIntentId) && $paymentIntentId !== '') {
            $this->stripe->fulfillPaymentIntent($paymentIntentId);
        }
    }

    /** @return array{0: bool, 1: string} */
    private function purchaseConversionContext(Request $request, $user, string $level): array
    {
        $sessionId = $request->query('session_id');
        $paymentIntentId = $request->query('payment_intent');

        if (is_string($sessionId) && $sessionId !== '') {
            $sessionKey = 'ga_purchase_conversion.'.$sessionId;

            if ($request->session()->has($sessionKey)) {
                return [false, $sessionId];
            }

            $request->session()->put($sessionKey, true);

            return [true, $sessionId];
        }

        if (is_string($paymentIntentId) && $paymentIntentId !== '') {
            $intentKey = 'ga_purchase_conversion.'.$paymentIntentId;

            if ($request->session()->has($intentKey)) {
                return [false, $paymentIntentId];
            }

            $request->session()->put($intentKey, true);

            return [true, $paymentIntentId];
        }

        if ($request->session()->pull('track_purchase_conversion')) {
            $transactionId = Payment::query()
                ->where('user_id', $user->id)
                ->where('certification_level', $level)
                ->where('status', Payment::STATUS_COMPLETED)
                ->latest('paid_at')
                ->value('stripe_payment_intent_id')
                ?? Payment::query()
                    ->where('user_id', $user->id)
                    ->where('certification_level', $level)
                    ->where('status', Payment::STATUS_COMPLETED)
                    ->latest('paid_at')
                    ->value('stripe_checkout_session_id')
                ?? Payment::query()
                    ->where('user_id', $user->id)
                    ->where('certification_level', $level)
                    ->where('status', Payment::STATUS_COMPLETED)
                    ->latest('paid_at')
                    ->value('reference')
                ?? '';

            return [true, (string) $transactionId];
        }

        return [false, ''];
    }
}
