<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExerciseScenarioCompletion extends Model
{
    protected $fillable = [
        'user_id',
        'guest_token',
        'device_id',
        'certification_level',
        'exercise_slug',
        'exercise_level',
        'scenario_index',
        'completed_at',
    ];

    protected $attributes = [
        'exercise_level' => 1,
    ];

    protected function casts(): array
    {
        return [
            'completed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
