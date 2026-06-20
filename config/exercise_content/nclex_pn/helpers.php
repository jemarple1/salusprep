<?php

if (! function_exists('nclex_levels')) {
    /**
     * Build 5 levels × 10 scenarios from scenario banks with progressive difficulty.
     *
     * @param  list<array<string, mixed>|callable(int, int): array<string, mixed>>  $banks
     * @return array{levels: array<int, list<array<string, mixed>>>}
     */
    function nclex_levels(array $banks): array
    {
        $levels = [];

        for ($level = 1; $level <= 5; $level++) {
            $levels[$level] = [];

            foreach (array_values($banks) as $index => $bank) {
                $levels[$level][] = is_callable($bank)
                    ? $bank($level, $index)
                    : nclex_enrich($bank, $level, $index);
            }
        }

        return ['levels' => $levels];
    }

    /** @param  array<string, mixed>  $base */
    function nclex_enrich(array $base, int $level, int $index): array
    {
        $scenario = $base;
        $prefix = match ($level) {
            1 => '',
            2 => ' The nurse has just received bedside report.',
            3 => ' Multiple patients on the unit are calling for assistance.',
            4 => ' The charge nurse asks you to prioritize quickly.',
            5 => ' Several actions seem reasonable — use nursing priority frameworks.',
            default => '',
        };

        if ($prefix !== '' && isset($scenario['scenario'])) {
            $scenario['scenario'] = rtrim($scenario['scenario']).$prefix;
        }

        if (isset($base['level_notes'][$level])) {
            $scenario['scenario'] = rtrim($scenario['scenario']).' '.$base['level_notes'][$level];
            unset($scenario['level_notes']);
        }

        if ($level >= 4 && isset($base['level_options'][$level])) {
            $scenario['options'] = $base['level_options'][$level];
        }

        if ($level >= 4 && isset($base['level_correct'][$level])) {
            $scenario['correct'] = $base['level_correct'][$level];
        }

        $scenario['title'] = ($base['title'] ?? 'Scenario').' · L'.$level;

        return $scenario;
    }

    /** @param  list<array<string, mixed>>  $items */
    function nclex_priority_items(array $items, int $level): array
    {
        $count = min(count($items), 3 + (int) floor($level / 2));

        return array_slice($items, 0, $count);
    }
}
