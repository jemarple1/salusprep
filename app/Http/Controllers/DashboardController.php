<?php

namespace App\Http\Controllers;

use App\Models\ExamSession;
use App\Services\AccuracyTrendService;
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
        AccuracyTrendService $accuracyTrend,
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

        $quizNumbers = ExamSession::quizNumbersForUser($user->id, $level);

        $categoryStats = collect();
        $overallStats = ['total' => 0, 'correct' => 0, 'incorrect' => 0, 'accuracy_percent' => 0];
        $wrongByCategory = [];
        $totalMissed = 0;
        $activeStudySession = null;
        $accuracyTrendData = ['points' => [], 'trend' => 'insufficient', 'trend_delta' => 0, 'trend_message' => '', 'total_quizzes' => 0];

        if ($unlocked) {
            $categoryStats = $proficiency->forUser($user, $level);
            $overallStats = $proficiency->overall($user, $level);
            $wrongByCategory = $study->wrongCountsByCategory($user, $level);
            $totalMissed = count($study->wrongQuestionIds($user, $level));
            $activeStudySession = $study->activeSession($user, $level);
            $accuracyTrendData = $accuracyTrend->forUser($user, $level);
        }

        return view('dashboard', [
            'sessions' => $sessions,
            'quizNumbers' => $quizNumbers,
            'unlocked' => $unlocked,
            'freeRemaining' => $access?->freeQuestionsRemaining() ?? CertificationLevel::FREE_QUESTIONS,
            'activeSession' => $user->activeExamSession($level),
            'categoryStats' => $categoryStats,
            'overallStats' => $overallStats,
            'wrongByCategory' => $wrongByCategory,
            'totalMissed' => $totalMissed,
            'activeStudySession' => $activeStudySession,
            'accuracyTrend' => $accuracyTrendData,
        ]);
    }
}
