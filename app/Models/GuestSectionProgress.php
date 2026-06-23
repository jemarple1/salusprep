<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuestSectionProgress extends Model
{
    protected $table = 'guest_section_progress';

    protected $fillable = [
        'device_id',
        'guest_token',
        'certification_level',
        'preview_started_at',
        'preview_actions_used',
    ];

    protected function casts(): array
    {
        return [
            'preview_started_at' => 'datetime',
        ];
    }
}
