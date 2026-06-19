<?php

namespace App\Support;

class CertificationLevel
{
    public const EMT_BASIC = 'emt_basic';

    public const EMT_ADVANCED = 'emt_advanced';

    public const PARAMEDIC = 'paramedic';

    public const NCLEX_PN = 'nclex_pn';

    public const NREMT_MARK = 'NREMT®';

    public const NCLEX_PN_MARK = 'NCLEX-PN®';

    public const QUIZ_QUESTIONS = 25;

    public const FOCUS_CATEGORY_PERCENT = 75;

    public const PRICE_CENTS = 899;

    /** @return array<string, string> */
    public static function slugs(): array
    {
        return [
            self::EMT_BASIC => 'emt-basic',
            self::EMT_ADVANCED => 'emt-advanced',
            self::PARAMEDIC => 'paramedic',
            self::NCLEX_PN => 'nclex-pn',
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
            self::NCLEX_PN => self::NCLEX_PN_MARK,
        ];
    }

    /** @return array<string, string> */
    public static function descriptions(): array
    {
        return [
            self::EMT_BASIC => self::NREMT_MARK.' prep at the EMT-Basic scope of practice.',
            self::EMT_ADVANCED => 'Advanced EMT (AEMT) adaptive practice for intermediate skills.',
            self::PARAMEDIC => 'Paramedic-level '.self::NREMT_MARK.' adaptive quizzes and critical care scenarios.',
            self::NCLEX_PN => self::NCLEX_PN_MARK.' adaptive practice for safe, effective practical nursing care.',
        ];
    }

    public static function headerTag(string $level): string
    {
        return self::isNclex($level) ? 'Prep' : self::NREMT_MARK;
    }

    public static function practiceHeadline(string $level): string
    {
        if (self::isNclex($level)) {
            return 'Adaptive '.self::label($level).' practice.';
        }

        return 'Adaptive '.self::NREMT_MARK.' practice for '.self::label($level).'.';
    }

    public static function unlockProductDescription(string $level): string
    {
        if (self::isNclex($level)) {
            return 'One-time Full Access for unlimited adaptive '.self::NCLEX_PN_MARK.' practice.';
        }

        return 'One-time Full Access for unlimited adaptive '.self::NREMT_MARK.' practice.';
    }

    public static function isNclex(string $level): bool
    {
        return $level === self::NCLEX_PN;
    }

    public static function platformSwitcherHint(string $activeLevel): string
    {
        $others = collect(self::all())
            ->reject(fn (string $l) => $l === $activeLevel)
            ->map(fn (string $l) => self::label($l))
            ->join(', ', ' and ');

        return "Use the ⛨ menu to switch to {$others} — each is a separate platform.";
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
