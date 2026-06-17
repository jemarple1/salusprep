<?php

namespace App\Support;

class CertificationLevel
{
    public const EMT_BASIC = 'emt_basic';

    public const EMT_ADVANCED = 'emt_advanced';

    public const PARAMEDIC = 'paramedic';

    public const FREE_QUESTIONS = 25;

    public const PRICE_CENTS = 899;

    /** @return array<string, string> */
    public static function slugs(): array
    {
        return [
            self::EMT_BASIC => 'emt-basic',
            self::EMT_ADVANCED => 'emt-advanced',
            self::PARAMEDIC => 'paramedic',
        ];
    }

    public static function slug(string $level): string
    {
        return self::slugs()[$level] ?? $level;
    }

    public static function fromSlug(string $slug): ?string
    {
        $match = array_search($slug, self::slugs(), true);

        return $match !== false ? $match : null;
    }

    public static function isValidSlug(string $slug): bool
    {
        return self::fromSlug($slug) !== null;
    }

    /** @return array<string, string> */
    public static function labels(): array
    {
        return [
            self::EMT_BASIC => 'EMT-Basic',
            self::EMT_ADVANCED => 'EMT-Advanced',
            self::PARAMEDIC => 'Paramedic',
        ];
    }

    /** @return array<string, string> */
    public static function descriptions(): array
    {
        return [
            self::EMT_BASIC => 'National Registry prep at the EMT-Basic scope of practice.',
            self::EMT_ADVANCED => 'Advanced EMT (AEMT) adaptive practice for intermediate skills.',
            self::PARAMEDIC => 'Paramedic-level NREMT adaptive quizzes and critical care scenarios.',
        ];
    }

    public static function label(string $level): string
    {
        return self::labels()[$level] ?? $level;
    }

    public static function isValid(string $level): bool
    {
        return array_key_exists($level, self::labels());
    }

    /** @return list<string> */
    public static function all(): array
    {
        return array_keys(self::labels());
    }
}
