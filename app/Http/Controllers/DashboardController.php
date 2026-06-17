<?php

namespace App\Http\Controllers;

use App\Services\CategoryProficiencyService;
use App\Services\StudyService;
use App\Support\CertificationLevel;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(
        Request $request,
        CategoryProficiencyService $proficiency,
        StudyService $study,
    ): View {
        $level = $request->attributes->get('certification_level');
        $user = $request->user();
        $access = $user->sectionAccessFor($level);
        $unlocked = $user->hasSectionAccess($level);

        $sessions = $user->examSessions()
            ->where('certification_level', $level)
            ->latest()
            ->limit(10)
            ->get();

        $categoryStats = collect();
        $overallStats = ['total' => 0, 'correct' => 0, 'incorrect' => 0, 'accuracy_percent' => 0];
        $wrongByCategory = [];
        $totalMissed = 0;
        $activeStudySession = null;

        if ($unlocked) {
            $categoryStats = $proficiency->forUser($user, $level);
            $overallStats = $proficiency->overall($user, $level);
            $wrongByCategory = $study->wrongCountsByCategory($user, $level);
            $totalMissed = count($study->wrongQuestionIds($user, $level));
            $activeStudySession = $study->activeSession($user, $level);
        }

        return view('dashboard', [
            'sessions' => $sessions,
            'unlocked' => $unlocked,
            'freeRemaining' => $access?->freeQuestionsRemaining() ?? CertificationLevel::FREE_QUESTIONS,
            'activeSession' => $user->activeExamSession($level),
            'categoryStats' => $categoryStats,
            'overallStats' => $overallStats,
            'wrongByCategory' => $wrongByCategory,
            'totalMissed' => $totalMissed,
            'activeStudySession' => $activeStudySession,
        ]);
    }
}
