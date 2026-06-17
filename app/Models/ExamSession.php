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

    public function targetQuestionCount(): int
    {
        return CertificationLevel::QUIZ_QUESTIONS;
    }

    public function progressPercent(): int
    {
        $target = $this->targetQuestionCount();

        if ($target === 0) {
            return 0;
        }

        return (int) round(min(100, ($this->questions_answered / $target) * 100));
    }

    public function hasReachedQuestionLimit(): bool
    {
        return $this->questions_answered >= $this->targetQuestionCount();
    }

    public function freeQuestionsRemaining(): int
    {
        if ($this->user_id !== null) {
            return $this->sectionAccess()?->freeQuestionsRemaining() ?? CertificationLevel::FREE_QUESTIONS;
        }

        return $this->guestProgress()?->freeQuestionsRemaining() ?? CertificationLevel::FREE_QUESTIONS;
    }

    /** @return array<int, int> Session ID => quiz number (1-based, per user/guest and certification level). */
    public static function quizNumbersForUser(int $userId, string $certificationLevel): array
    {
        $ids = static::query()
            ->where('user_id', $userId)
            ->where('certification_level', $certificationLevel)
            ->orderBy('created_at')
            ->orderBy('id')
            ->pluck('id');

        $numbers = [];
        foreach ($ids as $index => $id) {
            $numbers[$id] = $index + 1;
        }

        return $numbers;
    }

    public function userQuizNumber(): ?int
    {
        if ($this->user_id === null) {
            return null;
        }

        return static::quizNumbersForUser($this->user_id, $this->certification_level)[$this->id] ?? null;
    }
}
