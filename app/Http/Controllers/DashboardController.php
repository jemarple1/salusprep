<?php

namespace App\Http\Controllers;

use App\Models\ExamSession;
use App\Services\AccuracyTrendService;
use App\Services\CategoryProficiencyService;
use App\Services\GuestService;
use App\Services\StudyService;
use App\Models\StudySession;
use App\Support\CertificationLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private GuestService $guests) {}

    public function __invoke(
        Request $request,
        CategoryProficiencyService $proficiency,
        StudyService $study,
        AccuracyTrendService $accuracyTrend,
    ): View {
        $level = $request->attributes->get('certification_level');
        $user = $request->user();

        if ($user === null) {
            $guestToken = $this->guests->token($request);
            $progress = $this->guests->progress($guestToken, $level);

            return view('dashboard', $this->baseViewData(
                sessions: collect(),
                quizNumbers: [],
                unlocked: false,
                requiresAuth: true,
                freeRemaining: $progress->freeQuestionsRemaining(),
                activeSession: $this->guests->activeExamSession($guestToken, $level),
            ));
        }

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

        return view('dashboard', $this->baseViewData(
            sessions: $sessions,
            quizNumbers: $quizNumbers,
            unlocked: $unlocked,
            requiresAuth: false,
            freeRemaining: $access?->freeQuestionsRemaining() ?? CertificationLevel::FREE_QUESTIONS,
            activeSession: $user->activeExamSession($level),
            categoryStats: $categoryStats,
            overallStats: $overallStats,
            wrongByCategory: $wrongByCategory,
            totalMissed: $totalMissed,
            activeStudySession: $activeStudySession,
            accuracyTrend: $accuracyTrendData,
        ));
    }

    /** @param  Collection<int, ExamSession>|iterable<int, ExamSession>  $sessions */
    private function baseViewData(
        iterable $sessions,
        array $quizNumbers,
        bool $unlocked,
        bool $requiresAuth,
        int $freeRemaining,
        ?ExamSession $activeSession,
        Collection $categoryStats = new Collection,
        array $overallStats = ['total' => 0, 'correct' => 0, 'incorrect' => 0, 'accuracy_percent' => 0],
        array $wrongByCategory = [],
        int $totalMissed = 0,
        ?StudySession $activeStudySession = null,
        array $accuracyTrend = ['points' => [], 'trend' => 'insufficient', 'trend_delta' => 0, 'trend_message' => '', 'total_quizzes' => 0],
    ): array {
        return [
            'sessions' => $sessions,
            'quizNumbers' => $quizNumbers,
            'unlocked' => $unlocked,
            'requiresAuth' => $requiresAuth,
            'freeRemaining' => $freeRemaining,
            'activeSession' => $activeSession,
            'categoryStats' => $categoryStats,
            'overallStats' => $overallStats,
            'wrongByCategory' => $wrongByCategory,
            'totalMissed' => $totalMissed,
            'activeStudySession' => $activeStudySession,
            'accuracyTrend' => $accuracyTrend,
        ];
    }
}
