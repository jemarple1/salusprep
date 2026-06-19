<?php

namespace App\Support;

class BurnRegion
{
    private const ANTERIOR_ONLY = ['chest', 'abdomen'];

    private const POSTERIOR_ONLY = ['back'];

    /**
     * Normalize legacy region keys to sided keys for grading.
     *
     * @param  list<string>  $regions
     * @return list<string>
     */
    public static function normalizeKeys(array $regions): array
    {
        $normalized = [];

        foreach ($regions as $region) {
            if (str_contains($region, ':')) {
                $normalized[] = $region;

                continue;
            }

            if (in_array($region, self::ANTERIOR_ONLY, true)) {
                $normalized[] = 'anterior:'.$region;

                continue;
            }

            if (in_array($region, self::POSTERIOR_ONLY, true)) {
                $normalized[] = 'posterior:'.$region;

                continue;
            }

            $normalized[] = 'anterior:'.$region;
            $normalized[] = 'posterior:'.$region;
        }

        return collect($normalized)->unique()->sort()->values()->all();
    }
}
