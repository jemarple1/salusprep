<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Question extends Model
{
    protected $fillable = [
        'source_key',
        'category',
        'certification_level',
        'difficulty',
        'initial_difficulty',
        'difficulty_calibrated_at',
        'stem',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'correct_option',
        'explanation',
    ];

    protected function casts(): array
    {
        return [
            'difficulty_calibrated_at' => 'datetime',
        ];
    }

    public function answers(): HasMany
    {
        return $this->hasMany(ExamAnswer::class);
    }

    public function optionFor(string $letter): string
    {
        return match (strtoupper($letter)) {
            'A' => $this->option_a,
            'B' => $this->option_b,
            'C' => $this->option_c,
            'D' => $this->option_d,
            default => '',
        };
    }

    /** @return array<string, string> */
    public function options(): array
    {
        return [
            'A' => $this->option_a,
            'B' => $this->option_b,
            'C' => $this->option_c,
            'D' => $this->option_d,
        ];
    }

    public function platformCorrectPercent(): ?int
    {
        $stats = self::platformCorrectPercentsFor([$this->id]);

        return $stats[$this->id] ?? null;
    }

    /**
     * @param  list<int>  $questionIds
     * @return array<int, int>
     */
    public static function platformCorrectPercentsFor(array $questionIds): array
    {
        if ($questionIds === []) {
            return [];
        }

        $rows = DB::table('exam_answers')
            ->whereIn('question_id', $questionIds)
            ->groupBy('question_id')
            ->select('question_id')
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('SUM(CASE WHEN is_correct = 1 THEN 1 ELSE 0 END) as correct')
            ->get();

        $percents = [];

        foreach ($rows as $row) {
            if ((int) $row->total < 1) {
                continue;
            }

            $percents[(int) $row->question_id] = (int) round(((int) $row->correct / (int) $row->total) * 100);
        }

        return $percents;
    }
}
