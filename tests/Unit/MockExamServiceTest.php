<?php

namespace Tests\Unit;

use App\Models\ExamSession;
use App\Services\MockExamService;
use App\Support\CertificationLevel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MockExamServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_does_not_terminate_before_minimum_questions(): void
    {
        $session = ExamSession::query()->create([
            'user_id' => null,
            'guest_token' => 'test',
            'certification_level' => CertificationLevel::EMT_BASIC,
            'exam_type' => ExamSession::TYPE_MOCK,
            'questions_answered' => 50,
            'ability_estimate' => 0.9,
            'status' => ExamSession::STATUS_IN_PROGRESS,
        ]);

        $this->assertNull(app(MockExamService::class)->evaluateTermination($session));
    }

    public function test_terminates_pass_after_minimum_with_high_ability(): void
    {
        $session = ExamSession::query()->create([
            'user_id' => null,
            'guest_token' => 'test',
            'certification_level' => CertificationLevel::EMT_BASIC,
            'exam_type' => ExamSession::TYPE_MOCK,
            'questions_answered' => 72,
            'ability_estimate' => 0.8,
            'status' => ExamSession::STATUS_IN_PROGRESS,
        ]);

        $this->assertSame(ExamSession::MOCK_PASS, app(MockExamService::class)->evaluateTermination($session));
    }
}
