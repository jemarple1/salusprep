<?php

namespace App\Support;

use App\Models\User;

class PlatformExercise
{
    public const FREE_SCENARIOS_PER_EXERCISE = 1;

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

    /** @return list<array<string, mixed>> */
    public static function scenarios(string $level, string $slug): array
    {
        $content = self::content($level, $slug);

        return $content['scenarios'] ?? [];
    }

    /** @return array<string, mixed>|null */
    public static function scenario(string $level, string $slug, int $index): ?array
    {
        return self::scenarios($level, $slug)[$index] ?? null;
    }

    public static function canAccessScenario(?User $user, string $level, bool $unlocked, int $scenarioIndex): bool
    {
        if ($scenarioIndex < self::FREE_SCENARIOS_PER_EXERCISE) {
            return true;
        }

        if ($user === null) {
            return false;
        }

        return $unlocked || $user->hasSectionAccess($level);
    }

    /**
     * @return list<array<string, mixed>>
     */
    public static function cardsForLevel(string $level, ?User $user, bool $unlocked): array
    {
        return collect(self::forLevel($level))
            ->map(function (array $exercise) use ($level) {
                $scenarioCount = count(self::scenarios($level, $exercise['slug']));

                return [
                    ...$exercise,
                    'scenario_count' => $scenarioCount,
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
     * @return list<array{index: int, title: string, accessible: bool, url: string}>
     */
    public static function scenarioLinks(string $level, string $slug, ?User $user, bool $unlocked): array
    {
        $sectionSlug = CertificationLevel::slug($level);

        return collect(self::scenarios($level, $slug))
            ->values()
            ->map(fn (array $scenario, int $index) => [
                'index' => $index,
                'title' => $scenario['title'] ?? 'Scenario '.($index + 1),
                'accessible' => self::canAccessScenario($user, $level, $unlocked, $index),
                'url' => route('exercises.show', [
                    'section' => $sectionSlug,
                    'exercise' => $slug,
                    'scenario' => $index,
                ]),
            ])
            ->all();
    }
}
