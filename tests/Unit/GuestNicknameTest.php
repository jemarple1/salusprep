<?php

namespace Tests\Unit;

use App\Support\GuestNickname;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class GuestNicknameTest extends TestCase
{
    #[Test]
    public function it_generates_a_deterministic_nickname_from_device_id(): void
    {
        $deviceId = '4c36bf9f-2811-49df-be02-232f66a4bbcd';

        $first = GuestNickname::fromDeviceId($deviceId);
        $second = GuestNickname::fromDeviceId($deviceId);

        $this->assertSame($first, $second);
        $this->assertMatchesRegularExpression('/^[a-z]+ [a-z]+$/', $first);
    }
}
