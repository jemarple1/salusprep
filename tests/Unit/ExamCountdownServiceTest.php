<?php

namespace Tests\Unit;

use App\Services\ExamCountdownService;
use Carbon\Carbon;
use Tests\TestCase;

class ExamCountdownServiceTest extends TestCase
{
    public function test_returns_null_without_exam_date(): void
    {
        $service = new ExamCountdownService;

        $this->assertNull($service->forDate(null));
    }

    public function test_counts_days_until_future_exam(): void
    {
        Carbon::setTestNow('2026-06-19');

        $service = new ExamCountdownService;
        $result = $service->forDate('2026-07-01');

        $this->assertSame(12, $result['days']);
        $this->assertSame('12 days', $result['short_label']);
        $this->assertFalse($result['is_today']);
        $this->assertFalse($result['is_past']);

        Carbon::setTestNow();
    }

    public function test_marks_exam_day_as_today(): void
    {
        Carbon::setTestNow('2026-06-19');

        $service = new ExamCountdownService;
        $result = $service->forDate('2026-06-19');

        $this->assertTrue($result['is_today']);
        $this->assertSame('Today', $result['short_label']);

        Carbon::setTestNow();
    }
}
