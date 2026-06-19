<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    public static function get(string $key, ?string $default = null): ?string
    {
        $value = static::query()->where('key', $key)->value('value');

        return $value ?? $default;
    }

    public static function getInt(string $key, int $default): int
    {
        $value = static::get($key);

        if ($value === null || ! is_numeric($value)) {
            return $default;
        }

        return max(0, (int) $value);
    }

    public static function set(string $key, string $value): void
    {
        static::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $value],
        );
    }
}
