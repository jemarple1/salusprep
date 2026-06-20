<?php

namespace App\Http\Controllers;

use App\Models\ExamSession;
use App\Services\AccuracyTrendService;
use App\Services\CategoryProficiencyService;
use App\Services\FocusCategoryService;
use App\Services\FocusExamService;
use App\Services\GuestService;
use App\Services\PreviewAccessService;
use App\Services\StudyService;
use App\Models\StudySession;
use App\Services\MockExamService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private GuestService $guests,
        private PreviewAccessService $preview,
    ) {}

    public function __invoke(
        Request $request,
        CategoryProficiencyService $proficiency,
        StudyService $study,
        AccuracyTrendService $accuracyTrend,
        FocusCategoryService $focusCategory,
        FocusExamService $focusExams,
        MockExamService $mockExam,
    ): View {
        $level = $request->attributes->get('certification_level');
        $user = $request->user();
        $hasAccess = $this->preview->hasAccess($request, $level);

        if ($user === null) {
            $guestToken = $this->guests->token($request);
            $categoryStats = $hasAccess ? $proficiency->forGuest($guestToken, $level) : collect();
            $overallStats = $hasAccess
                ? $proficiency->overallForGuest($guestToken, $level)
                : ['total' => 0, 'correct' => 0, 'incorrect' => 0, 'accuracy_percent' => 0];

            $weakCategories = $categoryStats->sortBy('accuracy_percent')->take(3)->values();
            $focusExamOptions = $focusExams->optionsForLevel(
                $level,
                $categoryStats,
                ($overallStats['total'] ?? 0) > 0 ? $overallStats['accuracy_percent'] : null,
            );

            return view('dashboard', $this->baseViewData(
                sessions: collect(),
                quizNumbers: [],
                unlocked: false,
                hasAccess: $hasAccess,
                requiresAuth: true,
                activeSession: $this->guests->activeExamSession($guestToken, $level),
                categoryStats: $categoryStats,
                overallStats: $overallStats,
                weakCategories: $weakCategories,
                pinnedFocus: $focusCategory->resolvePinned($request, $level, $weakCategories),
                focusExamOptions: $focusExamOptions,
                mockExamState: [
                    'canStart' => false,
                    'activeSession' => null,
                    'completedToday' => false,
                    'todaysOutcome' => null,
                ],
            ));
        }

        $unlocked = $user->hasSectionAccess($level);

        $sessions = $user->examSessions()
            ->where('certification_level', $level)
            ->where(function ($query) {
                $query->where('exam_type', ExamSession::TYPE_QUIZ)
                    ->orWhereNull('exam_type');
            })
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

        if ($hasAccess) {
            $categoryStats = $proficiency->forUser($user, $level);
            $overallStats = $proficiency->overall($user, $level);
            $wrongByCategory = $study->wrongCountsByCategory($user, $level);
            $totalMissed = count($study->wrongQuestionIds($user, $level));
            $activeStudySession = $study->activeSession($user, $level);
            $accuracyTrendData = $accuracyTrend->forUser($user, $level);
        }

        $weakCategories = $categoryStats->sortBy('accuracy_percent')->take(3)->values();
        $pinnedFocus = $focusCategory->resolvePinned($request, $level, $weakCategories);
        $focusExamOptions = $focusExams->optionsForLevel(
            $level,
            $categoryStats,
            ($overallStats['total'] ?? 0) > 0 ? $overallStats['accuracy_percent'] : null,
        );

        $mockExamState = [
            'canStart' => false,
            'activeSession' => null,
            'completedToday' => false,
            'todaysOutcome' => null,
        ];

        if ($hasAccess) {
            $mockExamState = [
                'canStart' => $mockExam->canStartToday($user, $level),
                'activeSession' => $mockExam->activeSession($user, $level),
                'completedToday' => $mockExam->completedToday($user, $level),
                'todaysOutcome' => $mockExam->todaysOutcome($user, $level),
            ];
        }

        return view('dashboard', $this->baseViewData(
            sessions: $sessions,
            quizNumbers: $quizNumbers,
            unlocked: $unlocked,
            hasAccess: $hasAccess,
            requiresAuth: false,
            activeSession: $user->activeExamSession($level),
            categoryStats: $categoryStats,
            overallStats: $overallStats,
            wrongByCategory: $wrongByCategory,
            totalMissed: $totalMissed,
            activeStudySession: $activeStudySession,
            accuracyTrend: $accuracyTrendData,
            weakCategories: $weakCategories,
            pinnedFocus: $pinnedFocus,
            focusExamOptions: $focusExamOptions,
            mockExamState: $mockExamState,
        ));
    }

    /** @param  Collection<int, ExamSession>|iterable<int, ExamSession>  $sessions */
    private function baseViewData(
        iterable $sessions,
        array $quizNumbers,
        bool $unlocked,
        bool $hasAccess,
        bool $requiresAuth,
        ?ExamSession $activeSession,
        Collection $categoryStats = new Collection,
        array $overallStats = ['total' => 0, 'correct' => 0, 'incorrect' => 0, 'accuracy_percent' => 0],
        array $wrongByCategory = [],
        int $totalMissed = 0,
        ?StudySession $activeStudySession = null,
        array $accuracyTrend = ['points' => [], 'trend' => 'insufficient', 'trend_delta' => 0, 'trend_message' => '', 'total_quizzes' => 0],
        $weakCategories = new Collection,
        ?string $pinnedFocus = null,
        Collection $focusExamOptions = new Collection,
        array $mockExamState = [
            'canStart' => false,
            'activeSession' => null,
            'completedToday' => false,
            'todaysOutcome' => null,
        ],
    ): array {
        return [
            'sessions' => $sessions,
            'quizNumbers' => $quizNumbers,
            'unlocked' => $unlocked,
            'hasAccess' => $hasAccess,
            'requiresAuth' => $requiresAuth,
            'activeSession' => $activeSession,
            'categoryStats' => $categoryStats,
            'overallStats' => $overallStats,
            'wrongByCategory' => $wrongByCategory,
            'totalMissed' => $totalMissed,
            'activeStudySession' => $activeStudySession,
            'accuracyTrend' => $accuracyTrend,
            'weakCategories' => $weakCategories,
            'pinnedFocus' => $pinnedFocus,
            'focusExamOptions' => $focusExamOptions,
            'mockExamState' => $mockExamState,
        ];
    }
}
