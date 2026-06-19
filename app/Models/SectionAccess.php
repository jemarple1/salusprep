<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SectionAccess extends Model
{
    protected $fillable = [
        'user_id',
        'certification_level',
        'preview_actions_used',
        'unlocked_at',
        'pinned_focus_category',
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
}
