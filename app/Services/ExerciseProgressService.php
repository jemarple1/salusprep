<?php

namespace App\Services;

use App\Models\ExerciseScenarioCompletion;
use App\Models\User;
use App\Support\PlatformExercise;
use Illuminate\Http\Request;

class ExerciseProgressService
{
    public const SCENARIOS_PER_EXERCISE = 10;

    public function __construct(private GuestService $guests) {}

    /** @return list<int> */
    public function completedIndexes(
        Request $request,
        string $certificationLevel,
        string $exerciseSlug,
        int $exerciseLevel = 1,
    ): array {
        $query = ExerciseScenarioCompletion::query()
            ->where('certification_level', $certificationLevel)
            ->where('exercise_slug', $exerciseSlug)
            ->where('exercise_level', $exerciseLevel);

        if ($user = $request->user()) {
            $query->where('user_id', $user->id);
        } else {
            $query->where('guest_token', $this->guests->token($request));
        }

        return $query
            ->orderBy('scenario_index')
            ->pluck('scenario_index')
            ->map(fn ($index) => (int) $index)
            ->all();
    }

    public function isComplete(
        Request $request,
        string $certificationLevel,
        string $exerciseSlug,
        int $scenarioIndex,
        int $exerciseLevel = 1,
    ): bool {
        return in_array(
            $scenarioIndex,
            $this->completedIndexes($request, $certificationLevel, $exerciseSlug, $exerciseLevel),
            true,
        );
    }

    public function markComplete(
        Request $request,
        string $certificationLevel,
        string $exerciseSlug,
        int $scenarioIndex,
        int $exerciseLevel = 1,
    ): void {
        $attributes = [
            'certification_level' => $certificationLevel,
            'exercise_slug' => $exerciseSlug,
            'exercise_level' => $exerciseLevel,
            'scenario_index' => $scenarioIndex,
        ];

        if ($user = $request->user()) {
            ExerciseScenarioCompletion::query()->updateOrCreate(
                [...$attributes, 'user_id' => $user->id],
                ['guest_token' => null, 'completed_at' => now()],
            );

            return;
        }

        ExerciseScenarioCompletion::query()->updateOrCreate(
            [...$attributes, 'guest_token' => $this->guests->token($request)],
            [
                'user_id' => null,
                'device_id' => $this->guests->activityDeviceId($request),
                'completed_at' => now(),
            ],
        );
    }

    public function firstIncompleteIndex(
        Request $request,
        string $certificationLevel,
        string $exerciseSlug,
        int $exerciseLevel = 1,
    ): ?int {
        $completed = $this->completedIndexes($request, $certificationLevel, $exerciseSlug, $exerciseLevel);
        $total = count(PlatformExercise::scenarios($certificationLevel, $exerciseSlug, $exerciseLevel));

        for ($index = 0; $index < $total; $index++) {
            if (! in_array($index, $completed, true)) {
                return $index;
            }
        }

        return null;
    }

    public function nextIncompleteIndex(
        Request $request,
        string $certificationLevel,
        string $exerciseSlug,
        int $afterIndex,
        int $exerciseLevel = 1,
    ): ?int {
        $total = count(PlatformExercise::scenarios($certificationLevel, $exerciseSlug, $exerciseLevel));
        $completed = $this->completedIndexes($request, $certificationLevel, $exerciseSlug, $exerciseLevel);

        for ($index = $afterIndex + 1; $index < $total; $index++) {
            if (! in_array($index, $completed, true)) {
                return $index;
            }
        }

        return null;
    }

    public function isLevelComplete(
        Request $request,
        string $certificationLevel,
        string $exerciseSlug,
        int $exerciseLevel,
    ): bool {
        return $this->firstIncompleteIndex($request, $certificationLevel, $exerciseSlug, $exerciseLevel) === null
            && count($this->completedIndexes($request, $certificationLevel, $exerciseSlug, $exerciseLevel)) > 0;
    }

    /** @return list<int> */
    public function completedLevels(Request $request, string $certificationLevel, string $exerciseSlug): array
    {
        $levels = [];

        foreach (range(1, PlatformExercise::exerciseLevelCount($certificationLevel, $exerciseSlug)) as $level) {
            if ($this->isLevelComplete($request, $certificationLevel, $exerciseSlug, $level)) {
                $levels[] = $level;
            }
        }

        return $levels;
    }

    public function maxUnlockedLevel(Request $request, string $certificationLevel, string $exerciseSlug): int
    {
        $maxLevels = PlatformExercise::exerciseLevelCount($certificationLevel, $exerciseSlug);

        if ($maxLevels <= 1) {
            return 1;
        }

        for ($level = 1; $level < $maxLevels; $level++) {
            if (! $this->isLevelComplete($request, $certificationLevel, $exerciseSlug, $level)) {
                return $level;
            }
        }

        return $maxLevels;
    }

    /** @return array{exercise_level: int, scenario: int} */
    public function resumeTarget(Request $request, string $certificationLevel, string $exerciseSlug): array
    {
        if (PlatformExercise::hasExerciseLevels($certificationLevel, $exerciseSlug)) {
            $maxUnlocked = $this->maxUnlockedLevel($request, $certificationLevel, $exerciseSlug);

            for ($level = 1; $level <= $maxUnlocked; $level++) {
                $incomplete = $this->firstIncompleteIndex($request, $certificationLevel, $exerciseSlug, $level);

                if ($incomplete !== null) {
                    return ['exercise_level' => $level, 'scenario' => $incomplete];
                }
            }

            return ['exercise_level' => 1, 'scenario' => 0];
        }

        $incomplete = $this->firstIncompleteIndex($request, $certificationLevel, $exerciseSlug);

        return [
            'exercise_level' => 1,
            'scenario' => $incomplete ?? 0,
        ];
    }

    public function mergeGuestIntoUser(string $guestToken, User $user): void
    {
        $guestRows = ExerciseScenarioCompletion::query()
            ->where('guest_token', $guestToken)
            ->get();

        foreach ($guestRows as $row) {
            ExerciseScenarioCompletion::query()->updateOrCreate(
                [
                    'user_id' => $user->id,
                    'certification_level' => $row->certification_level,
                    'exercise_slug' => $row->exercise_slug,
                    'exercise_level' => $row->exercise_level,
                    'scenario_index' => $row->scenario_index,
                ],
                ['guest_token' => null, 'completed_at' => $row->completed_at],
            );
        }

        ExerciseScenarioCompletion::query()
            ->where('guest_token', $guestToken)
            ->delete();
    }
}
