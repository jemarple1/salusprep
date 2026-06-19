<?php

namespace App\Http\Controllers;

use App\Services\FocusCategoryService;
use App\Services\GuestService;
use App\Services\PreviewAccessService;
use App\Services\StripeCheckoutService;
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

        if ($user !== null) {
            $activeSession = $user->activeExamSession($level);

            return view('platform.home', [
                'unlocked' => $unlocked,
                'hasAccess' => $hasAccess,
                'activeSession' => $activeSession,
                'exercises' => PlatformExercise::cardsForLevel($level),
                'pinnedFocus' => $this->focusCategory->get($request, $level),
            ]);
        }

        $guestToken = $this->guests->token($request);
        $activeSession = $this->guests->activeExamSession($guestToken, $level);

        return view('platform.home', [
            'unlocked' => false,
            'hasAccess' => $hasAccess,
            'activeSession' => $activeSession,
            'exercises' => PlatformExercise::cardsForLevel($level),
            'pinnedFocus' => $this->focusCategory->get($request, $level),
        ]);
    }
}
