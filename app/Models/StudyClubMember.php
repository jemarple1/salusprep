<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudyClubMember extends Model
{
    protected $fillable = [
        'email',
        'device_id',
        'user_id',
        'certification_level',
        'joined_at',
        'unsubscribed_at',
        'unsubscribe_token',
    ];

    protected function casts(): array
    {
        return [
            'joined_at' => 'datetime',
            'unsubscribed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function guestDevice(): BelongsTo
    {
        return $this->belongsTo(GuestDevice::class, 'device_id', 'device_id');
    }

    public function isActive(): bool
    {
        return $this->unsubscribed_at === null;
    }
}
