<?php

namespace App\Support;

use App\Models\User;

class UserAvatar
{
    public const SYMBOL = '🧑‍⚕️';

    /** @var list<string> */
    public const COLORS = ['green', 'blue', 'purple', 'amber', 'teal'];

    public static function randomColor(): string
    {
        return self::COLORS[array_rand(self::COLORS)];
    }

    public static function colorFor(User $user): string
    {
        $color = $user->avatar_color;

        if (is_string($color) && in_array($color, self::COLORS, true)) {
            return $color;
        }

        return self::COLORS[$user->id % count(self::COLORS)];
    }

    public static function symbol(?string $color = null): string
    {
        return self::SYMBOL;
    }

    /** @return array{bg: string, ring: string, text: string, symbol: string} */
    public static function palette(?string $color = null): array
    {
        return [
            'bg' => 'bg-[#cce5f3]',
            'ring' => 'ring-ems/30',
            'text' => 'text-slate-700',
            'symbol' => self::SYMBOL,
        ];
    }
}
