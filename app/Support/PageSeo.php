<?php

namespace App\Support;

use Illuminate\Http\Request;

class PageSeo
{
    public const SITE_NAME = 'SalusPrep';

    public const SITE_TAGLINE = 'Adaptive Tests, Flashcards & More';

    /** @return array{title: string, description: string, robots: string} */
    public static function forPlatform(string $level): array
    {
        return [
            'title' => self::formatDocumentTitle(CertificationLevel::seoTitle($level)),
            'description' => CertificationLevel::seoDescription($level),
            'robots' => 'index, follow',
        ];
    }

    public static function platformPageTitle(string $level, string $pageLabel): string
    {
        return CertificationLevel::examMark($level).' '.CertificationLevel::label($level).' '.$pageLabel;
    }

    /** @param  array<string, mixed>  $exercise */
    public static function exercisePageTitle(string $level, array $exercise): string
    {
        return self::platformPageTitle($level, $exercise['title'].' — '.$exercise['category'].' Skill Exercise');
    }

    /** @param  array<string, mixed>  $exercise */
    public static function exercisePageDescription(string $level, array $exercise): string
    {
        $mark = CertificationLevel::examMark($level);
        $label = CertificationLevel::label($level);
        $summary = trim((string) ($exercise['description'] ?? ''));
        $howTo = trim((string) ($exercise['how_to'] ?? ''));
        $lead = $howTo !== '' ? $howTo : $summary;

        return "Interactive {$exercise['title']} drill for {$mark} {$label} exam prep. {$lead} Practice with instant feedback on SalusPrep.";
    }

    public static function skillsIndexDescription(string $level): string
    {
        $mark = CertificationLevel::examMark($level);
        $label = CertificationLevel::label($level);

        return match ($level) {
            CertificationLevel::NCLEX_PN => "Hands-on NCLEX-PN™ skill exercises for {$label}: prioritization, ADPIE, delegation, isolation precautions, medication safety, clinical scales, and more. Free preview drills with government-sourced review articles.",
            CertificationLevel::PARAMEDIC => "Paramedic skill exercises for {$mark} prep: patient assessment, 12-lead ECG, pharmacology, airway management, trauma, OB/neonatal scenarios, and ALS documentation. Interactive drills with .gov-sourced concept reviews.",
            default => "EMT skill exercises for {$mark} {$label} prep: SOAP charting, START/JumpSTART/SALT triage, GCS, burn TBSA, stroke scales, vitals interpretation, and pharmacology drills. Interactive scenarios with foundational review articles.",
        };
    }

    /** @return array{title: string, description: string, robots: string} */
    public static function forPlatformPage(string $level, string $pageLabel, ?string $description = null): array
    {
        return [
            'title' => self::formatDocumentTitle(self::platformPageTitle($level, $pageLabel)),
            'description' => $description ?? CertificationLevel::seoDescription($level),
            'robots' => 'index, follow',
        ];
    }

    /** @return array{title: string, description: string, robots: string} */
    public static function forSitePage(string $pageTitle, string $description, string $robots = 'index, follow'): array
    {
        return [
            'title' => self::formatDocumentTitle($pageTitle),
            'description' => $description,
            'robots' => $robots,
        ];
    }

    public static function formatDocumentTitle(string $pageTitle): string
    {
        return rtrim($pageTitle).' | '.self::SITE_NAME;
    }

    public static function siteDescription(): string
    {
        return 'SalusPrep offers adaptive NREMT® and NCLEX-PN™ exam prep with practice quizzes, flashcards built from your misses, hands-on skill exercises, and daily mock exams. Start free — no account required.';
    }

    public static function canonicalUrl(Request $request): string
    {
        return $request->url();
    }

    /** @return list<array{loc: string, changefreq: string, priority: string}> */
    public static function sitemapEntries(): array
    {
        $entries = [];

        foreach (CertificationLevel::slugs() as $slug) {
            $entries[] = [
                'loc' => url('/'.$slug),
                'changefreq' => 'weekly',
                'priority' => '1.0',
            ];
        }

        foreach ([
            'legal.about' => ['priority' => '0.6', 'changefreq' => 'monthly'],
            'legal.terms' => ['priority' => '0.3', 'changefreq' => 'yearly'],
            'legal.privacy' => ['priority' => '0.3', 'changefreq' => 'yearly'],
            'register' => ['priority' => '0.5', 'changefreq' => 'monthly'],
            'login' => ['priority' => '0.4', 'changefreq' => 'monthly'],
        ] as $route => $meta) {
            $entries[] = [
                'loc' => route($route),
                'changefreq' => $meta['changefreq'],
                'priority' => $meta['priority'],
            ];
        }

        return $entries;
    }

    /** @return array<string, mixed> */
    public static function platformStructuredData(string $level, string $url): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => CertificationLevel::seoTitle($level),
            'description' => CertificationLevel::seoDescription($level),
            'url' => $url,
            'isPartOf' => [
                '@type' => 'WebSite',
                'name' => self::SITE_NAME,
                'url' => url('/'),
            ],
            'about' => [
                '@type' => 'EducationalOccupationalProgram',
                'name' => CertificationLevel::examMark($level).' '.CertificationLevel::label($level).' exam preparation',
                'description' => CertificationLevel::descriptions()[$level],
            ],
        ];
    }
}
