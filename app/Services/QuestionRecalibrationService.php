<?php

namespace App\Services;

use App\Models\Question;
use Illuminate\Support\Facades\DB;

class QuestionRecalibrationService
{
    /** @return array{updated: int, skipped: int, total: int} */
    public function recalibrate(): array
    {
        $minAttempts = config('questions.recalibration.min_attempts', 30);
        $bands = config('questions.recalibration.bands', []);

        $stats = DB::table('exam_answers')
            ->select('question_id')
            ->selectRaw('COUNT(*) as attempts')
            ->selectRaw('SUM(CASE WHEN is_correct = 1 THEN 1 ELSE 0 END) as correct')
            ->groupBy('question_id')
            ->having('attempts', '>=', $minAttempts)
            ->get()
            ->keyBy('question_id');

        $updated = 0;
        $skipped = 0;

        Question::query()->chunkById(200, function ($questions) use ($stats, $bands, &$updated, &$skipped) {
            foreach ($questions as $question) {
                $row = $stats->get($question->id);

                if ($row === null) {
                    $skipped++;

                    continue;
                }

                $percent = ((int) $row->correct / (int) $row->attempts) * 100;
                $newDifficulty = $this->difficultyForPercent($percent, $bands);

                if ($question->initial_difficulty === null) {
                    $question->initial_difficulty = $question->difficulty;
                }

                if ($question->difficulty === $newDifficulty) {
                    $skipped++;

                    continue;
                }

                $question->difficulty = $newDifficulty;
                $question->difficulty_calibrated_at = now();
                $question->save();
                $updated++;
            }
        });

        return [
            'updated' => $updated,
            'skipped' => $skipped,
            'total' => Question::query()->count(),
        ];
    }

    /** @param  array<int, int>  $bands */
    public function difficultyForPercent(float $percent, array $bands): int
    {
        ksort($bands);

        $selected = 5;

        foreach ($bands as $difficulty => $minimumPercent) {
            if ($percent >= $minimumPercent) {
                $selected = (int) $difficulty;
                break;
            }
        }

        return $selected;
    }
}
