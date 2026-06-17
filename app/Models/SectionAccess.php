<?php

namespace App\Models;

use App\Support\CertificationLevel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SectionAccess extends Model
{
    protected $fillable = [
        'user_id',
        'certification_level',
        'free_questions_used',
        'unlocked_at',
    ];

    protected function casts(): array
    {
        return [
            'unlocked_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isUnlocked(): bool
    {
        return $this->unlocked_at !== null;
    }

    public function freeQuestionsRemaining(): int
    {
        if ($this->isUnlocked()) {
            return PHP_INT_MAX;
        }

        return max(0, CertificationLevel::FREE_QUESTIONS - $this->free_questions_used);
    }

    public function hasFreeQuestionsRemaining(): bool
    {
        return ! $this->isUnlocked() && $this->free_questions_used < CertificationLevel::FREE_QUESTIONS;
    }

    public function requiresPayment(): bool
    {
        return ! $this->isUnlocked() && $this->free_questions_used >= CertificationLevel::FREE_QUESTIONS;
    }
}
