<?php

namespace App\Http\Controllers;

use App\Services\GuestService;
use App\Services\StripeCheckoutService;
use App\Support\CertificationLevel;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PlatformController extends Controller
{
    public function __construct(
        private GuestService $guests,
        private StripeCheckoutService $stripe,
    ) {}

    public function __invoke(Request $request): View
    {
        $level = $request->attributes->get('certification_level');
        $user = $request->user();

        if ($request->query('checkout') === 'success' && $user !== null) {
            $sessionId = $request->query('session_id');
            if (is_string($sessionId) && $sessionId !== '') {
                $this->stripe->fulfillFromCheckoutSessionId($sessionId);
            }
        }

        if ($user !== null) {
            $access = $user->sectionAccessFor($level);
            $activeSession = $user->activeExamSession($level);

            return view('platform.home', [
                'unlocked' => $user->hasSectionAccess($level),
                'freeRemaining' => $access?->freeQuestionsRemaining() ?? CertificationLevel::FREE_QUESTIONS,
                'activeSession' => $activeSession,
            ]);
        }

        $guestToken = $this->guests->token($request);
        $progress = $this->guests->progress($guestToken, $level);
        $activeSession = $this->guests->activeExamSession($guestToken, $level);

        return view('platform.home', [
            'unlocked' => false,
            'freeRemaining' => $progress->freeQuestionsRemaining(),
            'activeSession' => $activeSession,
        ]);
    }
}
