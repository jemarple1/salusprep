<?php

namespace App\Models;

use App\Support\CertificationLevel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Schema;

class ExamSession extends Model
{
    private static ?bool $mockExamSchemaReady = null;

    public const STATUS_IN_PROGRESS = 'in_progress';

    public const STATUS_PAYWALL = 'paywall';

    public const STATUS_COMPLETED = 'completed';

    public const TYPE_QUIZ = 'quiz';

    public const TYPE_MOCK = 'mock';

    public const MOCK_PASS = 'pass';

    public const MOCK_FAIL = 'fail';

    protected $fillable = [
        'user_id',
        'guest_token',
        'certification_level',
        'exam_type',
        'focus_category',
        'current_difficulty',
        'questions_answered',
        'correct_count',
        'status',
        'completed_at',
        'expires_at',
        'mock_outcome',
        'ability_estimate',
    ];

    protected function casts(): array
    {
        return [
            'completed_at' => 'datetime',
            'expires_at' => 'datetime',
            'ability_estimate' => 'float',
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
        return false;
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

    public function hasFocusCategory(): bool
    {
        return $this->focus_category !== null && $this->focus_category !== '';
    }

    public function focusCategoryLabel(): ?string
    {
        return $this->focus_category;
    }

    public function isMockExam(): bool
    {
        return ($this->exam_type ?? self::TYPE_QUIZ) === self::TYPE_MOCK;
    }

    public function isTimedOut(): bool
    {
        return $this->expires_at !== null && now()->greaterThan($this->expires_at);
    }

    public function mockPassed(): bool
    {
        return $this->mock_outcome === self::MOCK_PASS;
    }

    public function targetQuestionCount(): int
    {
        if ($this->isMockExam()) {
            return \App\Services\MockExamService::MAX_QUESTIONS;
        }

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

    public static function mockExamSchemaReady(): bool
    {
        if (self::$mockExamSchemaReady === null) {
            self::$mockExamSchemaReady = Schema::hasColumn((new static)->getTable(), 'exam_type');
        }

        return self::$mockExamSchemaReady;
    }

    /** @param  Builder<static>  $query */
    public function scopeQuizzesOnly(Builder $query): Builder
    {
        if (! self::mockExamSchemaReady()) {
            return $query;
        }

        return $query->where(function (Builder $inner) {
            $inner->where('exam_type', self::TYPE_QUIZ)
                ->orWhereNull('exam_type');
        });
    }

    /** @return array<int, int> Session ID => quiz number (1-based, per user/guest and certification level). */
    public static function quizNumbersForUser(int $userId, string $certificationLevel): array
    {
        $ids = static::query()
            ->where('user_id', $userId)
            ->where('certification_level', $certificationLevel)
            ->quizzesOnly()
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
