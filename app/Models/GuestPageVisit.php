<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuestPageVisit extends Model
{
    protected $fillable = [
        'device_id',
        'path',
        'route_name',
        'visited_at',
    ];

    protected function casts(): array
    {
        return [
            'visited_at' => 'datetime',
        ];
    }

    public function device(): BelongsTo
    {
        return $this->belongsTo(GuestDevice::class, 'device_id', 'device_id');
    }

    public function pathLabel(): string
    {
        if ($this->route_name !== null && $this->route_name !== '') {
            return str_replace('.', ' › ', $this->route_name);
        }

        return '/'.$this->path;
    }
}
