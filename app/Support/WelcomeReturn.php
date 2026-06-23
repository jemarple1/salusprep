<?php

namespace App\Support;

use Illuminate\Http\Request;

class WelcomeReturn
{
    public const QUERY_PARAM = 'from';

    public const QUERY_VALUE = 'welcome';

    public static function sessionKey(string $sectionSlug): string
    {
        return 'welcome_checklist_return.'.$sectionSlug;
    }

    public static function mark(Request $request, string $sectionSlug): void
    {
        $request->session()->put(self::sessionKey($sectionSlug), true);
    }

    public static function clear(Request $request, string $sectionSlug): void
    {
        $request->session()->forget(self::sessionKey($sectionSlug));
    }

    public static function active(Request $request, string $sectionSlug): bool
    {
        if ($request->query(self::QUERY_PARAM) === self::QUERY_VALUE) {
            return true;
        }

        return (bool) $request->session()->get(self::sessionKey($sectionSlug), false);
    }

    public static function url(string $url): string
    {
        $separator = str_contains($url, '?') ? '&' : '?';

        return $url.$separator.self::QUERY_PARAM.'='.self::QUERY_VALUE;
    }
}
