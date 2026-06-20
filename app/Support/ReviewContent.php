<?php

namespace App\Support;

class ReviewContent
{
    /** @return list<array<string, mixed>> */
    public static function forLevel(string $level): array
    {
        $path = config_path("review/{$level}.php");

        if (! is_file($path)) {
            return [];
        }

        /** @var list<array<string, mixed>> */
        return require $path;
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
