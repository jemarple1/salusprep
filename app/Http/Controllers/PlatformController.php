<?php

namespace App\Http\Controllers;

use App\Models\ExamSession;
use App\Services\AdaptiveExamService;
use App\Services\FocusCategoryService;
use App\Services\GuestService;
use App\Services\PreviewAccessService;
use App\Services\StripeCheckoutService;
use App\Support\CertificationLevel;
use App\Support\PlatformExercise;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PlatformController extends Controller
{
    public function __construct(
        private GuestService $guests,
        private StripeCheckoutService $stripe,
        private PreviewAccessService $preview,
        private FocusCategoryService $focusCategory,
        private AdaptiveExamService $examService,
    ) {}

    public function __invoke(Request $request): View|RedirectResponse
    {
        $level = $request->attributes->get('certification_level');
        $user = $request->user();

        if ($request->query('checkout') === 'success' && $user !== null) {
            $sessionId = $request->query('session_id');
            if (is_string($sessionId) && $sessionId !== '') {
                $this->stripe->fulfillFromCheckoutSessionId($sessionId);
            }

            if ($user->fresh()->hasSectionAccess($level)) {
                return redirect()->route('platform.welcome', $request->attributes->get('section_slug'));
            }
        }

        $unlocked = $user !== null && $user->hasSectionAccess($level);
        $hasAccess = $this->preview->hasAccess($request, $level);
        $pinnedFocus = $this->focusCategory->get($request, $level);

        if ($user !== null) {
            $activeSession = $user->activeExamSession($level);

            return view('platform.home', $this->homeViewData(
                $level,
                $unlocked,
                $hasAccess,
                $activeSession,
                $pinnedFocus,
            ));
        }

        $guestToken = $this->guests->token($request);
        $activeSession = $this->guests->activeExamSession($guestToken, $level);

        return view('platform.home', $this->homeViewData(
            $level,
            false,
            $hasAccess,
            $activeSession,
            $pinnedFocus,
        ));
    }

    /** @return array<string, mixed> */
    private function homeViewData(
        string $level,
        bool $unlocked,
        bool $hasAccess,
        ?ExamSession $activeSession,
        ?string $pinnedFocus,
    ): array {
        $showPreviewQuestion = $activeSession === null && $hasAccess;

        return [
            'unlocked' => $unlocked,
            'hasAccess' => $hasAccess,
            'activeSession' => $activeSession,
            'exercises' => PlatformExercise::cardsForLevel($level),
            'pinnedFocus' => $pinnedFocus,
            'previewQuestion' => $showPreviewQuestion
                ? $this->examService->landingPreviewQuestion($level, $pinnedFocus)
                : null,
            'previewQuestionNumber' => 1,
            'previewQuestionTotal' => CertificationLevel::QUIZ_QUESTIONS,
        ];
    }
}
