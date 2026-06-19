<?php

namespace App\Services;

use App\Models\Question;
use App\Support\QuestionCategory;
use Illuminate\Support\Collection;

class FocusExamService
{
    public const GENERAL_LABEL = 'General knowledge';

    /**
     * @return Collection<int, object{
     *     category: string,
     *     focus_category: ?string,
     *     is_general: bool,
     *     accuracy_percent: ?int,
     *     total: int,
     * }>
     */
    public function optionsForLevel(
        string $certificationLevel,
        Collection $categoryStats,
        ?int $overallAccuracy = null,
    ): Collection {
        $statsByCategory = $categoryStats->keyBy('category');

        $categories = Question::query()
            ->where('certification_level', $certificationLevel)
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        $categoryOptions = $categories
            ->map(function (string $category) use ($statsByCategory) {
                $stat = $statsByCategory->get($category);

                return (object) [
                    'category' => $category,
                    'focus_category' => $category,
                    'is_general' => false,
                    'accuracy_percent' => $stat?->accuracy_percent,
                    'total' => $stat?->total ?? 0,
                    'sort_rank' => $stat !== null ? $stat->accuracy_percent : 1000,
                ];
            })
            ->sortBy(fn ($option) => [$option->sort_rank, $option->category])
            ->values();

        $general = (object) [
            'category' => self::GENERAL_LABEL,
            'focus_category' => null,
            'is_general' => true,
            'accuracy_percent' => $overallAccuracy,
            'total' => 0,
            'sort_rank' => -1,
        ];

        return collect([$general])->concat($categoryOptions);
    }

    /** @return array<string, string> */
    public static function generalStyles(): array
    {
        return QuestionCategory::styles('Medical');
    }
}
