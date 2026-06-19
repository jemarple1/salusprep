<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AuthController;
use App\Services\CategoryProficiencyService;
use App\Services\FocusCategoryService;
use App\Services\GuestService;
use App\Services\PreviewAccessService;
use App\Services\StudyService;
use App\Support\PlatformExercise;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PlatformPaywallController extends Controller
{
    public function __construct(
        private PreviewAccessService $preview,
        private GuestService $guests,
        private CategoryProficiencyService $proficiency,
        private StudyService $study,
        private FocusCategoryService $focusCategory,
    ) {}

    public function __invoke(Request $request): View|RedirectResponse
    {
        $level = $request->attributes->get('certification_level');
        $slug = $request->attributes->get('section_slug');

        if ($this->preview->hasAccess($request, $level)) {
            return redirect()->route('platform.home', $slug);
        }

        $user = $request->user();
        $guestToken = $user === null ? $this->guests->token($request) : null;

        $categoryStats = $this->proficiency->forLearner($request, $this->guests, $level)
            ->filter(fn ($row) => $row->total >= 1)
            ->sortBy('accuracy_percent')
            ->values();

        $overallStats = $this->proficiency->overallForLearner($request, $this->guests, $level);

        $weakCategories = $categoryStats->take(3)->values();

        $wrongByCategory = $user !== null
            ? $this->study->wrongCountsByCategory($user, $level)
            : ($guestToken !== null ? $this->study->wrongCountsByCategoryForGuest($guestToken, $level) : []);

        $totalMissed = array_sum($wrongByCategory);
        $flashcardPreviews = $this->study->previewMissedQuestions($user, $guestToken, $level, 1);

        $exerciseCards = PlatformExercise::cardsForLevel($level);

        $pinnedFocus = $this->focusCategory->resolvePinned($request, $level, $weakCategories);

        if ($user === null) {
            $request->session()->put(AuthController::PAYWALL_CHECKOUT_SESSION_KEY, $slug);
        }

        return view('platform.paywall', [
            'categoryStats' => $categoryStats,
            'weakCategories' => $weakCategories,
            'overallStats' => $overallStats,
            'totalMissed' => $totalMissed,
            'wrongByCategory' => $wrongByCategory,
            'flashcardPreviews' => $flashcardPreviews,
            'exerciseCards' => $exerciseCards,
            'topWeakCategory' => $weakCategories->first(),
            'requiresAuth' => $user === null,
            'learnerName' => $user?->name,
            'pinnedFocus' => $pinnedFocus,
        ]);
    }
}
