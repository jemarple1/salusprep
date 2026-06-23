<?php

namespace App\Services;

use App\Models\ExamSession;
use App\Models\ExerciseScenarioCompletion;
use App\Models\SectionAccess;
use App\Models\User;
use App\Support\CertificationLevel;
use App\Support\PlatformExercise;
use App\Support\WelcomeReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class WelcomeDailyPlanService
{
    public const SKILLS_REQUIRED = 3;

    public const QUIZZES_REQUIRED = 2;

    public const MOCKS_REQUIRED = 1;

    public function __construct(private MockExamService $mockExam) {}

    public function showDailyPlan(SectionAccess $access, User $user): bool
    {
        return true;
    }

    public function isFirstDay(SectionAccess $access, User $user): bool
    {
        return $this->daysSinceUnlock($access, $user) === 0;
    }

    public function daysSinceUnlock(SectionAccess $access, User $user): int
    {
        $anchor = $access->unlocked_at ?? $access->created_at ?? $user->created_at;

        return max(0, $anchor->copy()->startOfDay()->diffInDays(today()));
    }

    /** @return array<string, mixed> */
    public function forUser(
        Request $request,
        User $user,
        string $level,
        SectionAccess $access,
        ?object $focusOption,
    ): array {
        $dayNumber = $this->daysSinceUnlock($access, $user) + 1;
        $recommendedSkills = $this->recommendedSkills($level, $this->daysSinceUnlock($access, $user));
        $quizzesCompletedToday = $this->quizzesCompletedToday($user, $level);
        $mockCompletedToday = $this->mockExam->completedToday($request, $level);
        $mockActive = $this->mockExam->activeSession($request, $level);

        $items = [];

        foreach ($recommendedSkills as $index => $exercise) {
            $completed = $this->skillCompletedToday($user, $level, $exercise['slug']);

            $items[] = [
                'key' => 'skill:'.$exercise['slug'],
                'type' => 'skill',
                'label' => $exercise['title'],
                'description' => 'Complete at least one scenario in this skill drill.',
                'completed' => $completed,
                'url' => WelcomeReturn::url($exercise['url']),
            ];
        }

        for ($quizNumber = 1; $quizNumber <= self::QUIZZES_REQUIRED; $quizNumber++) {
            $items[] = [
                'key' => 'quiz:'.$quizNumber,
                'type' => 'quiz',
                'label' => 'Adaptive quiz '.$quizNumber,
                'description' => $quizNumber === 1
                    ? 'Take your first 25-question focus or adaptive quiz today.'
                    : 'Take a second quiz to reinforce weak categories.',
                'completed' => $quizzesCompletedToday >= $quizNumber,
                'url' => WelcomeReturn::url(route('platform.dashboard', CertificationLevel::slug($level))),
            ];
        }

        $mockUrl = $mockActive !== null
            ? WelcomeReturn::url(route('mock-exam.show', [CertificationLevel::slug($level), $mockActive]))
            : WelcomeReturn::url(route('platform.home', CertificationLevel::slug($level)));

        $items[] = [
            'key' => 'mock:1',
            'type' => 'mock',
            'label' => 'Daily mock exam',
            'description' => 'One timed pass/fail mock exam per day — same pressure as test day.',
            'completed' => $mockCompletedToday,
            'url' => $mockUrl,
        ];

        $completedCount = collect($items)->where('completed', true)->count();
        $totalCount = count($items);

        return [
            'showDailyPlan' => true,
            'isFirstDay' => $this->isFirstDay($access, $user),
            'dayNumber' => $dayNumber,
            'planDate' => today()->format('l, F j'),
            'items' => $items,
            'completedCount' => $completedCount,
            'totalCount' => $totalCount,
            'progressPercent' => $totalCount > 0 ? (int) round(($completedCount / $totalCount) * 100) : 0,
            'isComplete' => $completedCount === $totalCount,
            'recommendedSkills' => $recommendedSkills,
            'quizzesCompletedToday' => $quizzesCompletedToday,
            'focusOption' => $focusOption,
        ];
    }

    /** @return list<array<string, mixed>> */
    public function recommendedSkills(string $level, int $daysSinceUnlock): array
    {
        $cards = PlatformExercise::cardsForLevel($level);

        if ($cards === []) {
            return [];
        }

        $offset = $daysSinceUnlock % count($cards);
        $rotated = array_merge(
            array_slice($cards, $offset),
            array_slice($cards, 0, $offset),
        );

        return array_slice($rotated, 0, self::SKILLS_REQUIRED);
    }

    /** @return list<array<string, mixed>> */
    public function checklistItemsForEmail(User $user, string $level, SectionAccess $access): array
    {
        $daysSinceUnlock = $this->daysSinceUnlock($access, $user);
        $recommendedSkills = $this->recommendedSkills($level, $daysSinceUnlock);
        $slug = CertificationLevel::slug($level);
        $items = [];

        foreach ($recommendedSkills as $exercise) {
            $items[] = [
                'type' => 'skill',
                'label' => $exercise['title'],
                'description' => 'Complete at least one scenario in this skill drill.',
            ];
        }

        for ($quizNumber = 1; $quizNumber <= self::QUIZZES_REQUIRED; $quizNumber++) {
            $items[] = [
                'type' => 'quiz',
                'label' => 'Adaptive quiz '.$quizNumber,
                'description' => $quizNumber === 1
                    ? 'Take your first 25-question focus or adaptive quiz today.'
                    : 'Take a second quiz to reinforce weak categories.',
            ];
        }

        $items[] = [
            'type' => 'mock',
            'label' => 'Daily mock exam',
            'description' => 'One timed pass/fail mock exam per day — same pressure as test day.',
        ];

        return $items;
    }

    public function quizzesCompletedToday(User $user, string $level): int
    {
        return ExamSession::query()
            ->where('user_id', $user->id)
            ->where('certification_level', $level)
            ->quizzesOnly()
            ->where('status', ExamSession::STATUS_COMPLETED)
            ->whereDate('completed_at', today())
            ->count();
    }

    public function skillCompletedToday(User $user, string $level, string $exerciseSlug): bool
    {
        return ExerciseScenarioCompletion::query()
            ->where('user_id', $user->id)
            ->where('certification_level', $level)
            ->where('exercise_slug', $exerciseSlug)
            ->whereDate('completed_at', today())
            ->exists();
    }

    /** @return array<string, mixed> */
    public function onboardingPlan(): array
    {
        return [
            'showDailyPlan' => false,
            'dayNumber' => 1,
            'planDate' => today()->format('l, F j'),
            'items' => [],
            'completedCount' => 0,
            'totalCount' => 0,
            'progressPercent' => 0,
            'isComplete' => false,
            'recommendedSkills' => [],
            'quizzesCompletedToday' => 0,
            'focusOption' => null,
        ];
    }
}
