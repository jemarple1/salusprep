<?php

namespace App\Support;

class ReviewContent
{
    /** @return list<array<string, mixed>> */
    public static function forLevel(string $level): array
    {
        $path = config_path("review/{$level}.php");
        $articles = is_file($path) ? require $path : [];

        $exercisePath = config_path("review/exercises/{$level}.php");
        if (is_file($exercisePath)) {
            /** @var list<array<string, mixed>> $exerciseArticles */
            $exerciseArticles = require $exercisePath;
            $articles = array_merge($articles, $exerciseArticles);
        }

        /** @var list<array<string, mixed>> */
        return $articles;
    }

    /** @return array<string, mixed>|null */
    public static function forExercise(string $level, string $exerciseSlug): ?array
    {
        $reviewSlug = PlatformExercise::reviewSlug($level, $exerciseSlug);

        return self::find($level, $reviewSlug);
    }

    /** @return array<string, mixed>|null */
    public static function linkedExercise(string $level, array $concept): ?array
    {
        $slug = $concept['exercise_slug'] ?? null;

        if ($slug === null) {
            foreach (PlatformExercise::forLevel($level) as $exercise) {
                $meta = PlatformExercise::find($level, $exercise['slug']);
                if ($meta === null) {
                    continue;
                }

                if (($meta['review_slug'] ?? $exercise['slug']) === ($concept['slug'] ?? '')) {
                    $slug = $exercise['slug'];
                    break;
                }
            }
        }

        if ($slug === null) {
            return null;
        }

        $exercise = PlatformExercise::find($level, $slug);

        if ($exercise === null) {
            return null;
        }

        return [
            ...$exercise,
            'url' => route('exercises.show', [
                'section' => CertificationLevel::slug($level),
                'exercise' => $slug,
            ]),
        ];
    }

    public static function hasArticles(string $level): bool
    {
        return self::forLevel($level) !== [];
    }

    /** @return array<string, mixed>|null */
    public static function find(string $level, string $slug): ?array
    {
        foreach (self::forLevel($level) as $article) {
            if ($article['slug'] === $slug) {
                return $article;
            }
        }

        return null;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public static function search(string $level, ?string $query): array
    {
        $articles = self::forLevel($level);

        if ($query === null || trim($query) === '') {
            return $articles;
        }

        $needle = mb_strtolower(trim($query));

        return array_values(array_filter($articles, function (array $article) use ($needle): bool {
            $haystack = mb_strtolower(implode(' ', [
                $article['title'] ?? '',
                $article['excerpt'] ?? '',
                $article['category'] ?? '',
                implode(' ', $article['keywords'] ?? []),
            ]));

            return str_contains($haystack, $needle);
        }));
    }
}
