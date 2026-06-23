<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Mail\ResetPasswordMail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;

#[Fillable(['name', 'avatar_color', 'email', 'password', 'google_id', 'facebook_id', 'twitter_id', 'last_login_at', 'terms_accepted_at', 'marketing_emails_opt_in', 'signup_country_code', 'signup_country_name', 'signup_latitude', 'signup_longitude', 'preview_started_at', 'preview_extension_granted_at', 'preview_extension_ends_at'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public function sendPasswordResetNotification(#[\SensitiveParameter] $token): void
    {
        Mail::to($this->email)->send(new ResetPasswordMail($this, $token));
    }

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

    public function studySessions(): HasMany
    {
        return $this->hasMany(StudySession::class);
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
            ->where('status', ExamSession::STATUS_IN_PROGRESS);

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
            'last_login_at' => 'datetime',
            'terms_accepted_at' => 'datetime',
            'preview_started_at' => 'datetime',
            'preview_extension_granted_at' => 'datetime',
            'preview_extension_ends_at' => 'datetime',
            'marketing_emails_opt_in' => 'boolean',
            'password' => 'hashed',
        ];
    }
}
