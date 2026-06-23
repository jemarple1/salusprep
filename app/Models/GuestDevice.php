<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GuestDevice extends Model
{
    protected $primaryKey = 'device_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'device_id',
        'first_ip',
        'country_code',
        'country_name',
        'latitude',
        'longitude',
        'referrer',
        'referrer_host',
        'landing_path',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'first_seen_at',
        'last_seen_at',
        'total_active_seconds',
        'converted_user_id',
        'converted_at',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'float',
            'longitude' => 'float',
            'first_seen_at' => 'datetime',
            'last_seen_at' => 'datetime',
            'converted_at' => 'datetime',
            'total_active_seconds' => 'integer',
        ];
    }

    public function convertedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'converted_user_id');
    }

    public function sectionProgress(): HasMany
    {
        return $this->hasMany(GuestSectionProgress::class, 'device_id', 'device_id');
    }

    public function examSessions(): HasMany
    {
        return $this->hasMany(ExamSession::class, 'device_id', 'device_id');
    }

    public function studySessions(): HasMany
    {
        return $this->hasMany(StudySession::class, 'device_id', 'device_id');
    }

    public function exerciseCompletions(): HasMany
    {
        return $this->hasMany(ExerciseScenarioCompletion::class, 'device_id', 'device_id');
    }

    public function formattedActiveTime(): string
    {
        $seconds = max(0, (int) $this->total_active_seconds);

        if ($seconds < 60) {
            return $seconds.'s';
        }

        $minutes = intdiv($seconds, 60);

        if ($minutes < 60) {
            return $minutes.'m';
        }

        $hours = intdiv($minutes, 60);
        $remainingMinutes = $minutes % 60;

        return $remainingMinutes > 0
            ? "{$hours}h {$remainingMinutes}m"
            : "{$hours}h";
    }

    public function referralLabel(): string
    {
        if ($this->utm_source !== null && $this->utm_source !== '') {
            $parts = array_filter([
                $this->utm_source,
                $this->utm_medium,
                $this->utm_campaign,
            ]);

            return implode(' / ', $parts);
        }

        if ($this->referrer_host !== null && $this->referrer_host !== '') {
            return $this->referrer_host;
        }

        return 'Direct';
    }
}
