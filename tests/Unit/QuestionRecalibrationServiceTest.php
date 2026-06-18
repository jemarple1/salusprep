<?php

namespace Tests\Unit;

use App\Services\QuestionRecalibrationService;
use PHPUnit\Framework\TestCase;

class QuestionRecalibrationServiceTest extends TestCase
{
    public function test_difficulty_for_percent_uses_configured_bands(): void
    {
        $service = new QuestionRecalibrationService;

        $bands = [
            1 => 85,
            2 => 70,
            3 => 50,
            4 => 35,
            5 => 0,
        ];

        $this->assertSame(1, $service->difficultyForPercent(90, $bands));
        $this->assertSame(2, $service->difficultyForPercent(75, $bands));
        $this->assertSame(3, $service->difficultyForPercent(60, $bands));
        $this->assertSame(4, $service->difficultyForPercent(40, $bands));
        $this->assertSame(5, $service->difficultyForPercent(20, $bands));
    }
}
