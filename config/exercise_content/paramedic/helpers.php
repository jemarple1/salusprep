<?php

if (! function_exists('paramedic_levels')) {
    /**
     * Build 5 levels × 10 scenarios from banks with progressive EMS difficulty.
     *
     * @param  list<array<string, mixed>|callable(int, int): array<string, mixed>>  $banks
     * @return array{levels: array<int, list<array<string, mixed>>>}
     */
    function paramedic_levels(array $banks): array
    {
        $levels = [];

        for ($level = 1; $level <= 5; $level++) {
            $levels[$level] = [];

            foreach (array_values($banks) as $index => $bank) {
                $levels[$level][] = is_callable($bank)
                    ? $bank($level, $index)
                    : paramedic_enrich($bank, $level, $index);
            }
        }

        return ['levels' => $levels];
    }

    /** @param  array<string, mixed>  $base */
    function paramedic_enrich(array $base, int $level, int $index): array
    {
        $scenario = $base;
        $prefix = match ($level) {
            1 => '',
            2 => ' Reassessment after your first intervention shows partial improvement.',
            3 => ' A second patient on scene requires your partner; resources are limited.',
            4 => ' Transport time to the appropriate facility is 25+ minutes.',
            5 => ' The patient\'s condition is evolving — prioritize per NHTSA EMS assessment principles.',
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
}
