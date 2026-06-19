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
        'preview_actions_used',
    ];
}
