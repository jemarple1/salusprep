<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudySession extends Model
{
    public const STATUS_IN_PROGRESS = 'in_progress';

    public const STATUS_COMPLETED = 'completed';

    protected $fillable = [
        'user_id',
        'guest_token',
        'certification_level',
        'filter_category',
        'deck',
        'initial_deck_size',
        'cards_studied',
        'status',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'deck' => 'array',
            'completed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isComplete(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function remainingCount(): int
    {
        return count($this->deck ?? []);
    }

    public function progressPercent(): int
    {
        if ($this->initial_deck_size === 0) {
            return 100;
        }

        return (int) round(min(100, ($this->cards_studied / $this->initial_deck_size) * 100));
    }

    public function masteredCount(): int
    {
        return max(0, $this->initial_deck_size - $this->remainingCount());
    }

    public function currentQuestionId(): ?int
    {
        $deck = $this->deck ?? [];

        return $deck[0] ?? null;
    }

    public function currentQuestion(): ?Question
    {
        $id = $this->currentQuestionId();

        return $id ? Question::find($id) : null;
    }
}
