<?php

namespace App\Support;

class GuestNickname
{
    /** @var list<string> */
    private const ADJECTIVES = [
        'amber', 'bashful', 'bouncy', 'brave', 'bright', 'calm', 'clever', 'cosmic',
        'curious', 'dapper', 'dazzling', 'eager', 'gentle', 'gleaming', 'golden',
        'happy', 'hidden', 'jolly', 'lopsided', 'lucky', 'mellow', 'merry', 'mighty',
        'nimble', 'patient', 'playful', 'quiet', 'rusty', 'sleepy', 'sneaky', 'spicy',
        'sunny', 'swift', 'tidy', 'tiny', 'velvet', 'wandering', 'wise', 'yellow',
        'zesty', 'zippy',
    ];

    /** @var list<string> */
    private const NOUNS = [
        'badger', 'bear', 'beetle', 'bunny', 'cardinal', 'crane', 'cricket', 'crow',
        'dolphin', 'duck', 'eagle', 'falcon', 'ferret', 'finch', 'fox', 'frog',
        'giraffe', 'goose', 'hamster', 'hawk', 'heron', 'koala', 'lemur', 'llama',
        'lynx', 'moose', 'narwhal', 'octopus', 'otter', 'owl', 'panda', 'parrot',
        'pelican', 'penguin', 'rabbit', 'raven', 'robin', 'salmon', 'seal', 'sparrow',
        'squirrel', 'starling', 'tortoise', 'toucan', 'turtle', 'walrus', 'whale',
        'wolf', 'wombat',
    ];

    public static function fromDeviceId(string $deviceId): string
    {
        $hash = crc32($deviceId);
        $adjective = self::ADJECTIVES[abs($hash) % count(self::ADJECTIVES)];
        $noun = self::NOUNS[(intdiv(abs($hash), count(self::ADJECTIVES))) % count(self::NOUNS)];

        return "{$adjective} {$noun}";
    }
}
