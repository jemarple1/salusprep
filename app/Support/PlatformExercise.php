<?php

namespace App\Support;

use App\Models\User;

class PlatformExercise
{
    /** @return list<array<string, mixed>> */
    public static function forLevel(string $level): array
    {
        return config("exercises.{$level}", []);
    }

    public static function hasExercises(string $level): bool
    {
        return self::forLevel($level) !== [];
    }

    /** @return array<string, mixed>|null */
    public static function find(string $level, string $slug): ?array
    {
        foreach (self::forLevel($level) as $exercise) {
            if ($exercise['slug'] === $slug) {
                return $exercise;
            }
        }

        return null;
    }

    /** @return array<string, mixed>|null */
    public static function content(string $level, string $slug): ?array
    {
        $content = config("exercise_content.{$level}", []);

        return $content[$slug] ?? null;
    }

    public static function hasExerciseLevels(string $level, string $slug): bool
    {
        return self::exerciseLevelCount($level, $slug) > 1;
    }

    public static function exerciseLevelCount(string $level, string $slug): int
    {
        return (int) (self::find($level, $slug)['levels'] ?? 1);
    }

    /** @return list<array<string, mixed>> */
    public static function scenarios(string $level, string $slug, int $exerciseLevel = 1): array
    {
        if ($slug === 'soap-charting') {
            return self::soapScenarios($level, $exerciseLevel);
        }

        $content = self::content($level, $slug);

        if (isset($content['levels'])) {
            $scenarios = array_values($content['levels'][$exerciseLevel] ?? []);

            return array_map(
                fn (array $scenario) => self::normalizeScenario($level, $slug, $scenario),
                $scenarios,
            );
        }

        $base = $content['scenarios'] ?? [];
        $addons = config("exercise_content_addons.{$level}.{$slug}", []);
        $scenarios = array_values(array_merge($base, $addons));

        return array_map(
            fn (array $scenario) => self::normalizeScenario($level, $slug, $scenario),
            $scenarios,
        );
    }

    /** @param  array<string, mixed>  $scenario */
    private static function normalizeScenario(string $level, string $slug, array $scenario): array
    {
        $meta = self::find($level, $slug);
        $ui = $meta['ui'] ?? $meta['type'] ?? '';

        if ($ui === 'adpie-sort') {
            $scenario['sections'] = [
                'A' => 'Assessment',
                'D' => 'Diagnosis',
                'P' => 'Planning',
                'I' => 'Implementation',
                'E' => 'Evaluation',
            ];
        }

        if ($slug === 'pharma-assist') {
            return self::normalizePharmaAssistScenario($scenario);
        }

        return $scenario;
    }

    /** @return list<array<string, mixed>> */
    private static function soapScenarios(string $level, int $exerciseLevel): array
    {
        if ($exerciseLevel === 1) {
            $content = self::content($level, 'soap-charting');
            $scenarios = array_values($content['scenarios'] ?? []);
        } else {
            $levels = config("exercise_content.{$level}.soap_charting_levels", []);
            $scenarios = array_values($levels[$exerciseLevel] ?? []);
        }

        return array_map(
            fn (array $scenario) => self::normalizeSoapScenario($scenario),
            $scenarios,
        );
    }

    /** @param  array<string, mixed>  $scenario */
    private static function normalizeSoapScenario(array $scenario): array
    {
        $scenario['sections'] = [
            'S' => 'Subjective',
            'O' => 'Objective',
            'A' => 'Assessment',
            'P' => 'Plan',
        ];

        return $scenario;
    }

    /** @param  array<string, mixed>  $scenario */
    private static function normalizePharmaAssistScenario(array $scenario): array
    {
        if (isset($scenario['options'])) {
            return $scenario;
        }

        $correctYes = ($scenario['correct'] ?? '') === 'yes';

        $scenario['scenario'] = $scenario['scenario'] ?? $scenario['prompt'] ?? '';
        $scenario['question'] = $scenario['question'] ?? 'Should you assist with this medication per protocol?';
        $scenario['options'] = [
            'assist' => 'Yes — assist per protocol',
            'withhold' => 'No — withhold and transport',
            'monitor' => 'No — monitor only, no medication',
        ];
        $scenario['correct'] = $correctYes ? 'assist' : 'withhold';

        return $scenario;
    }

    /** @return array<string, mixed>|null */
    public static function scenario(string $level, string $slug, int $index, int $exerciseLevel = 1): ?array
    {
        return self::scenarios($level, $slug, $exerciseLevel)[$index] ?? null;
    }

    public static function canAccessScenario(bool $hasPlatformAccess): bool
    {
        return $hasPlatformAccess;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public static function cardsForLevel(string $level): array
    {
        return collect(self::forLevel($level))
            ->map(function (array $exercise) use ($level) {
                $levelCount = self::exerciseLevelCount($level, $exercise['slug']);
                $scenarioCount = count(self::scenarios($level, $exercise['slug']));

                return [
                    ...$exercise,
                    'scenario_count' => $scenarioCount,
                    'level_count' => $levelCount,
                    'url' => route('exercises.show', [
                        'section' => CertificationLevel::slug($level),
                        'exercise' => $exercise['slug'],
                    ]),
                ];
            })
            ->values()
            ->all();
    }

    /**
     * @return list<array{index: int, title: string, accessible: bool, completed: bool, url: string}>
     */
    public static function scenarioLinks(
        string $level,
        string $slug,
        bool $hasPlatformAccess,
        array $completedIndexes = [],
        int $exerciseLevel = 1,
    ): array {
        $sectionSlug = CertificationLevel::slug($level);

        return collect(self::scenarios($level, $slug, $exerciseLevel))
            ->values()
            ->map(fn (array $scenario, int $index) => [
                'index' => $index,
                'title' => $scenario['title'] ?? 'Scenario '.($index + 1),
                'accessible' => self::canAccessScenario($hasPlatformAccess),
                'completed' => in_array($index, $completedIndexes, true),
                'url' => route('exercises.show', array_filter([
                    'section' => $sectionSlug,
                    'exercise' => $slug,
                    'level' => $exerciseLevel > 1 ? $exerciseLevel : null,
                    'scenario' => $index,
                ])),
            ])
            ->all();
    }

    /**
     * @return list<array{level: int, completed: bool, unlocked: bool, accessible: bool, current: bool, url: string}>
     */
    public static function levelLinks(
        string $level,
        string $slug,
        bool $hasPlatformAccess,
        array $completedLevels,
        int $maxUnlockedLevel,
        int $currentLevel,
    ): array {
        $sectionSlug = CertificationLevel::slug($level);

        return collect(range(1, self::exerciseLevelCount($level, $slug)))
            ->map(fn (int $levelNum) => [
                'level' => $levelNum,
                'completed' => in_array($levelNum, $completedLevels, true),
                'unlocked' => $levelNum <= $maxUnlockedLevel,
                'accessible' => self::canAccessScenario($hasPlatformAccess),
                'current' => $levelNum === $currentLevel,
                'url' => route('exercises.show', array_filter([
                    'section' => $sectionSlug,
                    'exercise' => $slug,
                    'level' => $levelNum > 1 ? $levelNum : null,
                    'scenario' => 0,
                ])),
            ])
            ->all();
    }
}
