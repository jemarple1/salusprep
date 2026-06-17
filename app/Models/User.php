<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public function examSessions(): HasMany
    {
        return $this->hasMany(ExamSession::class);
    }

    public function sectionAccesses(): HasMany
    {
        return $this->hasMany(SectionAccess::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function hasSectionAccess(string $certificationLevel): bool
    {
        return $this->sectionAccesses()
            ->where('certification_level', $certificationLevel)
            ->whereNotNull('unlocked_at')
            ->exists();
    }

    public function sectionAccessFor(string $certificationLevel): ?SectionAccess
    {
        return $this->sectionAccesses()
            ->where('certification_level', $certificationLevel)
            ->first();
    }

    public function activeExamSession(?string $certificationLevel = null): ?ExamSession
    {
        $query = $this->examSessions()
            ->whereIn('status', [ExamSession::STATUS_IN_PROGRESS, ExamSession::STATUS_PAYWALL]);

        if ($certificationLevel !== null) {
            $query->where('certification_level', $certificationLevel);
        }

        return $query->latest()->first();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
