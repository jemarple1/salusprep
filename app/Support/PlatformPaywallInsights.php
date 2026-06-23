<?php

namespace App\Support;

class PlatformPaywallInsights
{
    /** @return array{struggle_intro: string, struggles: list<array{topic: string, detail: string}>, help_intro: string, helps: list<string>}|null */
    public static function forLevel(string $level): ?array
    {
        $insights = config('paywall_insights.'.$level);

        return is_array($insights) ? $insights : null;
    }
}
