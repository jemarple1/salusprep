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
    public static function scenarioLinks(string $level, string $slug, bool $hasPlatformAccess): array
    {
        $sectionSlug = CertificationLevel::slug($level);

        return collect(self::scenarios($level, $slug))
            ->values()
            ->map(fn (array $scenario, int $index) => [
                'index' => $index,
                'title' => $scenario['title'] ?? 'Scenario '.($index + 1),
                'accessible' => self::canAccessScenario($hasPlatformAccess),
                'url' => route('exercises.show', [
                    'section' => $sectionSlug,
                    'exercise' => $slug,
                    'scenario' => $index,
                ]),
            ])
            ->all();
    }
}
