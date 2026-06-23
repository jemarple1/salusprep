<?php

namespace App\Support;

class CertificationLevel
{
    public const EMT_BASIC = 'emt_basic';

    public const EMT_ADVANCED = 'emt_advanced';

    public const PARAMEDIC = 'paramedic';

    public const NCLEX_PN = 'nclex_pn';

    public const NREMT_MARK = 'NREMT®';

    public const NCLEX_MARK = 'NCLEX®';

    public const NCLEX_PN_MARK = 'NCLEX-PN®';

    public const NCLEX_PN_TM = 'NCLEX-PN™';

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
            self::NCLEX_PN => 'Practical Nurse',
        ];
    }

    /** @return array<string, string> */
    public static function descriptions(): array
    {
        return [
            self::EMT_BASIC => self::NREMT_MARK.' prep at the EMT-Basic scope of practice.',
            self::EMT_ADVANCED => 'Advanced EMT (AEMT) adaptive practice for intermediate skills.',
            self::PARAMEDIC => 'Paramedic-level '.self::NREMT_MARK.' adaptive quizzes and critical care scenarios.',
            self::NCLEX_PN => self::NCLEX_MARK.' adaptive practice for safe, effective practical nursing care.',
        ];
    }

    public static function examMark(string $level): string
    {
        return self::isNclex($level) ? self::NCLEX_PN_TM : self::NREMT_MARK;
    }

    public static function registerAccent(string $level): string
    {
        return match ($level) {
            self::EMT_BASIC => 'ems',
            self::EMT_ADVANCED => 'safety',
            self::PARAMEDIC => 'medic',
            self::NCLEX_PN => 'pharma',
            default => 'medic',
        };
    }

    public static function headerTag(string $level): string
    {
        return self::isNclex($level) ? self::NCLEX_MARK : self::NREMT_MARK;
    }

    public static function practiceHeadline(string $level): string
    {
        if (self::isNclex($level)) {
            return 'Adaptive '.self::NCLEX_PN_TM.' practice for '.self::label($level).'.';
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

    public static function mockExamLandingHint(string $level): string
    {
        $exam = self::isNclex($level) ? self::NCLEX_MARK : self::NREMT_MARK;

        return "The daily mock exam works like the real {$exam} — adaptive, timed, and pass-or-fail only. You can take it once per day.";
    }

    public static function seoTitle(string $level): string
    {
        $exam = self::examMark($level);
        $platform = self::label($level);

        return "{$exam} {$platform} Prep — ".PageSeo::SITE_TAGLINE;
    }

    public static function seoDescription(string $level): string
    {
        return match ($level) {
            self::EMT_BASIC => 'Prepare for the NREMT® EMT-Basic exam with adaptive practice tests, flashcards from your missed questions, triage and pharmacology skill drills, and a daily mock exam. Free preview — no signup required.',
            self::EMT_ADVANCED => 'NREMT® Advanced EMT (AEMT) prep with adaptive quizzes, focus exams by topic, flashcards, IV and advanced airway skill exercises, and daily mock exams. Start practicing free today.',
            self::PARAMEDIC => 'NREMT® Paramedic exam prep with adaptive tests, ALS scenario simulators, pharmacology drills, flashcards, and timed daily mock exams. Practice critical care judgment under real exam pressure.',
            self::NCLEX_PN => 'NCLEX-PN™ Practical Nurse prep with adaptive practice tests, ADPIE and prioritization exercises, flashcards from your misses, and NCLEX®-style daily mock exams. Start your free preview now.',
            default => PageSeo::siteDescription(),
        };
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

    /** @return list<array{title: string, levels: list<string>}> */
    public static function platformSwitcherGroups(): array
    {
        return [
            [
                'title' => self::NREMT_MARK,
                'levels' => [self::EMT_BASIC, self::EMT_ADVANCED, self::PARAMEDIC],
            ],
            [
                'title' => self::NCLEX_MARK,
                'levels' => [self::NCLEX_PN],
            ],
        ];
    }
}
