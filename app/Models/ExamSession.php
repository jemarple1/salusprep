<?php

namespace App\Models;

use App\Support\CertificationLevel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ExamSession extends Model
{
    public const STATUS_IN_PROGRESS = 'in_progress';

    public const STATUS_PAYWALL = 'paywall';

    public const STATUS_COMPLETED = 'completed';

    protected $fillable = [
        'user_id',
        'guest_token',
        'certification_level',
        'current_difficulty',
        'questions_answered',
        'correct_count',
        'status',
        'completed_at',
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

    public function answers(): HasMany
    {
        return $this->hasMany(ExamAnswer::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function isGuest(): bool
    {
        return $this->user_id === null && $this->guest_token !== null;
    }

    public function sectionAccess(): ?SectionAccess
    {
        if ($this->user_id === null) {
            return null;
        }

        return SectionAccess::query()
            ->where('user_id', $this->user_id)
            ->where('certification_level', $this->certification_level)
            ->first();
    }

    public function guestProgress(): ?GuestSectionProgress
    {
        if ($this->guest_token === null) {
            return null;
        }

        return GuestSectionProgress::query()
            ->where('guest_token', $this->guest_token)
            ->where('certification_level', $this->certification_level)
            ->first();
    }

    public function sectionIsUnlocked(): bool
    {
        return $this->sectionAccess()?->isUnlocked() ?? false;
    }

    public function requiresPayment(): bool
    {
        return $this->status === self::STATUS_PAYWALL;
    }

    public function isComplete(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function certificationLabel(): string
    {
        return CertificationLevel::label($this->certification_level);
    }

    public function scorePercent(): int
    {
        if ($this->questions_answered === 0) {
            return 0;
        }

        return (int) round(($this->correct_count / $this->questions_answered) * 100);
    }

    public function freeQuestionsRemaining(): int
    {
        if ($this->user_id !== null) {
            return $this->sectionAccess()?->freeQuestionsRemaining() ?? CertificationLevel::FREE_QUESTIONS;
        }

        return $this->guestProgress()?->freeQuestionsRemaining() ?? CertificationLevel::FREE_QUESTIONS;
    }
}
