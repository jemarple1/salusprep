<?php

namespace App\Support;

class AccuracyDisplay
{
    /** @return array{bg: string, border: string, label: string, text: string} */
    public static function tier(?int $accuracyPercent, bool $hasData): array
    {
        if (! $hasData) {
            return [
                'bg' => 'bg-slate-700/80',
                'border' => 'border-slate-500/40',
                'label' => 'text-slate-400',
                'text' => 'text-slate-200',
            ];
        }

        $accuracy = $accuracyPercent ?? 0;

        if ($accuracy >= 85) {
            return [
                'bg' => 'bg-medic/35',
                'border' => 'border-medic/50',
                'label' => 'text-medic-light',
                'text' => 'text-white',
            ];
        }

        if ($accuracy >= 75) {
            return [
                'bg' => 'bg-ems/35',
                'border' => 'border-ems/50',
                'label' => 'text-ems-light',
                'text' => 'text-white',
            ];
        }

        if ($accuracy >= 60) {
            return [
                'bg' => 'bg-safety/35',
                'border' => 'border-safety/50',
                'label' => 'text-safety-light',
                'text' => 'text-white',
            ];
        }

        return [
            'bg' => 'bg-rescue/35',
            'border' => 'border-rescue/50',
            'label' => 'text-red-200',
            'text' => 'text-white',
        ];
    }
}
