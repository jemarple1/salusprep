<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreviewDevice extends Model
{
    public $incrementing = false;

    protected $primaryKey = 'device_id';

    protected $keyType = 'string';

    protected $fillable = [
        'device_id',
        'preview_started_at',
    ];

    protected function casts(): array
    {
        return [
            'preview_started_at' => 'datetime',
        ];
    }
}
