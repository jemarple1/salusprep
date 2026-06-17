<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    protected $fillable = [
        'category',
        'certification_level',
        'difficulty',
        'stem',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'correct_option',
        'explanation',
    ];

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
}
