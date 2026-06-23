<?php

namespace App\Support;

use App\Models\ExamSession;
use Illuminate\Support\Collection;

class ExamReviewRecommendations
{
    /** @var array<string, list<string>> */
    private const QUESTION_TO_EXERCISE_CATEGORIES = [
        'Airway' => ['Assessment', 'Triage'],
        'Cardiology' => ['Assessment', 'Pharmacology'],
        'Trauma' => ['Triage', 'Assessment'],
        'Medical' => ['Assessment', 'Pharmacology'],
        'OB/Peds' => ['Triage', 'Assessment'],
        'Operations' => ['Documentation', 'Triage'],
        'IV Therapy' => ['Assessment', 'Pharmacology'],
        'Adult Health' => ['Prioritization', 'Assessment', 'Clinical Judgment'],
        'Basic Care' => ['Assessment', 'Prioritization'],
        'Health Promotion' => ['Assessment', 'Communication'],
        'Maternal/Child' => ['Prioritization', 'Assessment'],
        'Pharmacology' => ['Pharmacology'],
        'Psychosocial' => ['Communication', 'Clinical Judgment'],
        'Risk Reduction' => ['Infection Control', 'Assessment'],
        'Safe Care' => ['Prioritization', 'Clinical Judgment', 'Delegation'],
    ];

    /**
     * @return Collection<string, int>
     */
    public static function weakCategories(ExamSession $session): Collection
    {
        return $session->answers
            ->where('is_correct', false)
            ->groupBy(fn ($answer) => $answer->question->category)
            ->map->count()
            ->sortDesc();
    }

    /**
     * @return object{
     *     category: string,
     *     focus_category: string,
     *     is_general: bool,
     *     accuracy_percent: ?int,
     *     miss_count: int,
     * }|null
     */
    public static function focusOptionForSession(ExamSession $session): ?object
    {
        $weak = self::weakCategories($session);

        if ($weak->isEmpty()) {
            return null;
        }

        $topCategory = $weak->keys()->first();
        $answersInCategory = $session->answers->filter(
            fn ($answer) => $answer->question->category === $topCategory,
        );
        $correct = $answersInCategory->where('is_correct', true)->count();
        $total = $answersInCategory->count();

        return (object) [
            'category' => $topCategory,
            'focus_category' => $topCategory,
            'is_general' => false,
            'accuracy_percent' => $total > 0 ? (int) round(($correct / $total) * 100) : null,
            'miss_count' => (int) $weak->first(),
        ];
    }

    /**
     * @return object{
     *     category: string,
     *     focus_category: null,
     *     is_general: bool,
     *     accuracy_percent: int,
     * }
     */
    public static function generalExamOption(ExamSession $session): object
    {
        return (object) [
            'category' => \App\Services\FocusExamService::GENERAL_LABEL,
            'focus_category' => null,
            'is_general' => true,
            'accuracy_percent' => $session->scorePercent(),
        ];
    }

    /**
     * @return list<array{exercise: array<string, mixed>, matched_category: string, miss_count: int}>
     */
    public static function suggestedExercises(string $level, Collection $weakCategories, int $limit = 3): array
    {
        if ($weakCategories->isEmpty() || ! PlatformExercise::hasExercises($level)) {
            return [];
        }

        $exercises = PlatformExercise::cardsForLevel($level);
        $scored = [];

        foreach ($exercises as $exercise) {
            $bestCategory = null;
            $bestScore = 0;

            foreach ($weakCategories as $questionCategory => $missCount) {
                if (! self::exerciseMatchesQuestionCategory($questionCategory, $exercise)) {
                    continue;
                }

                if ($missCount > $bestScore) {
                    $bestScore = $missCount;
                    $bestCategory = $questionCategory;
                }
            }

            if ($bestScore > 0) {
                $scored[] = [
                    'exercise' => $exercise,
                    'matched_category' => $bestCategory,
                    'miss_count' => $bestScore,
                    'score' => $bestScore,
                ];
            }
        }

        usort($scored, fn (array $a, array $b) => $b['score'] <=> $a['score']);

        return array_map(
            fn (array $row) => [
                'exercise' => $row['exercise'],
                'matched_category' => $row['matched_category'],
                'miss_count' => $row['miss_count'],
            ],
            array_slice($scored, 0, $limit),
        );
    }

    /** @param  array<string, mixed>  $exercise */
    private static function exerciseMatchesQuestionCategory(string $questionCategory, array $exercise): bool
    {
        $exerciseCategory = $exercise['category'] ?? '';

        if (in_array($exerciseCategory, self::QUESTION_TO_EXERCISE_CATEGORIES[$questionCategory] ?? [], true)) {
            return true;
        }

        return QuestionCategory::color($questionCategory) === ($exercise['color'] ?? null);
    }
}
