<?php

namespace App\Support;

use App\Models\User;

class UserAvatar
{
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

    public static function symbol(string $color): string
    {
        return match ($color) {
            'blue' => '🩺',
            'purple' => '💊',
            'amber' => '⛑',
            'teal' => '🏥',
            default => '⚕',
        };
    }

    /** @return array{bg: string, ring: string, text: string, symbol: string} */
    public static function palette(string $color): array
    {
        return match ($color) {
            'blue' => [
                'bg' => 'bg-ems/20',
                'ring' => 'ring-ems/35',
                'text' => 'text-ems-light',
                'symbol' => self::symbol('blue'),
            ],
            'purple' => [
                'bg' => 'bg-pharma/20',
                'ring' => 'ring-pharma/35',
                'text' => 'text-pharma-light',
                'symbol' => self::symbol('purple'),
            ],
            'amber' => [
                'bg' => 'bg-safety/20',
                'ring' => 'ring-safety/35',
                'text' => 'text-safety-light',
                'symbol' => self::symbol('amber'),
            ],
            'teal' => [
                'bg' => 'bg-teal-500/20',
                'ring' => 'ring-teal-400/35',
                'text' => 'text-teal-300',
                'symbol' => self::symbol('teal'),
            ],
            default => [
                'bg' => 'bg-medic/20',
                'ring' => 'ring-medic/35',
                'text' => 'text-medic-light',
                'symbol' => self::symbol('green'),
            ],
        };
    }
}
