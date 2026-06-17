<?php

namespace App\Http\Controllers;

use App\Support\CertificationLevel;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $level = $request->attributes->get('certification_level');
        $user = $request->user();
        $access = $user->sectionAccessFor($level);

        $sessions = $user->examSessions()
            ->where('certification_level', $level)
            ->latest()
            ->limit(10)
            ->get();

        return view('dashboard', [
            'sessions' => $sessions,
            'unlocked' => $user->hasSectionAccess($level),
            'freeRemaining' => $access?->freeQuestionsRemaining() ?? CertificationLevel::FREE_QUESTIONS,
            'activeSession' => $user->activeExamSession($level),
        ]);
    }
}
