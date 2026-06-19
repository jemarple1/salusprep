<?php

namespace App\Services;

use Carbon\Carbon;
use Carbon\CarbonInterface;

class ExamCountdownService
{
    /**
     * @return array{
     *     days: int,
     *     label: string,
     *     short_label: string,
     *     is_today: bool,
     *     is_past: bool,
     *     exam_date: string,
     * }|null
     */
    public function forDate(CarbonInterface|string|null $examDate): ?array
    {
        if ($examDate === null) {
            return null;
        }

        $date = $examDate instanceof CarbonInterface
            ? $examDate->copy()->startOfDay()
            : Carbon::parse($examDate)->startOfDay();

        $today = now()->startOfDay();
        $days = (int) $today->diffInDays($date, false);

        if ($days < 0) {
            return [
                'days' => abs($days),
                'label' => 'Exam date passed',
                'short_label' => 'Passed',
                'is_today' => false,
                'is_past' => true,
                'exam_date' => $date->toDateString(),
            ];
        }

        if ($days === 0) {
            return [
                'days' => 0,
                'label' => 'Exam is today!',
                'short_label' => 'Today',
                'is_today' => true,
                'is_past' => false,
                'exam_date' => $date->toDateString(),
            ];
        }

        if ($days === 1) {
            return [
                'days' => 1,
                'label' => '1 day until your exam',
                'short_label' => '1 day',
                'is_today' => false,
                'is_past' => false,
                'exam_date' => $date->toDateString(),
            ];
        }

        return [
            'days' => $days,
            'label' => "{$days} days until your exam",
            'short_label' => "{$days} days",
            'is_today' => false,
            'is_past' => false,
            'exam_date' => $date->toDateString(),
        ];
    }
}
