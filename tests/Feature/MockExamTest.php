<?php

namespace Tests\Feature;

use App\Models\ExamSession;
use App\Models\Question;
use App\Models\User;
use App\Services\MockExamService;
use App\Support\CertificationLevel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MockExamTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_start_mock_exam_during_preview(): void
    {
        Question::query()->create([
            'source_key' => 'mock-guest-1',
            'certification_level' => CertificationLevel::EMT_BASIC,
            'category' => 'Airway',
            'difficulty' => 3,
            'initial_difficulty' => 3,
            'stem' => 'Guest mock question',
            'option_a' => 'A',
            'option_b' => 'B',
            'option_c' => 'C',
            'option_d' => 'D',
            'correct_option' => 'A',
        ]);

        $response = $this->post('/emt-basic/mock-exam/start');

        $session = ExamSession::query()->first();
        $this->assertNotNull($session);
        $this->assertNull($session->user_id);
        $this->assertNotNull($session->guest_token);
        $this->assertSame(ExamSession::TYPE_MOCK, $session->exam_type);

        $response->assertRedirect(route('mock-exam.show', ['emt-basic', $session]));
    }

    public function test_home_shows_mock_exam_start_for_guest_in_preview(): void
    {
        $this->get('/emt-basic')
            ->assertOk()
            ->assertSee('Start the daily mock exam')
            ->assertSee(route('mock-exam.start', 'emt-basic'), false);
    }

    public function test_dashboard_shows_accuracy_and_mock_exam_cards(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/emt-basic/dashboard')
            ->assertOk()
            ->assertSee('Overall accuracy')
            ->assertSee('Daily mock exam')
            ->assertDontSee('Full Access ·');
    }

    public function test_user_can_start_mock_exam_once_per_day(): void
    {
        $user = User::factory()->create();

        Question::query()->create([
            'source_key' => 'mock-1',
            'certification_level' => CertificationLevel::EMT_BASIC,
            'category' => 'Airway',
            'difficulty' => 3,
            'initial_difficulty' => 3,
            'stem' => 'Mock question one',
            'option_a' => 'A',
            'option_b' => 'B',
            'option_c' => 'C',
            'option_d' => 'D',
            'correct_option' => 'A',
        ]);

        $response = $this->actingAs($user)
            ->post('/emt-basic/mock-exam/start');

        $session = ExamSession::query()->first();
        $this->assertNotNull($session);
        $this->assertSame(ExamSession::TYPE_MOCK, $session->exam_type);
        $this->assertNotNull($session->expires_at);

        $response->assertRedirect(route('mock-exam.show', ['emt-basic', $session]));

        $this->actingAs($user)
            ->post('/emt-basic/mock-exam/start')
            ->assertRedirect(route('mock-exam.show', ['emt-basic', $session]));

        $session->update([
            'status' => ExamSession::STATUS_COMPLETED,
            'completed_at' => now(),
            'mock_outcome' => ExamSession::MOCK_PASS,
        ]);

        $this->actingAs($user)
            ->post('/emt-basic/mock-exam/start')
            ->assertRedirect(route('platform.dashboard', 'emt-basic'))
            ->assertSessionHasErrors('mock_exam');
    }

    public function test_mock_exam_show_has_timer_and_no_score(): void
    {
        $user = User::factory()->create();

        Question::query()->create([
            'source_key' => 'mock-2',
            'certification_level' => CertificationLevel::EMT_BASIC,
            'category' => 'Airway',
            'difficulty' => 3,
            'initial_difficulty' => 3,
            'stem' => 'Mock question two',
            'option_a' => 'A',
            'option_b' => 'B',
            'option_c' => 'C',
            'option_d' => 'D',
            'correct_option' => 'A',
        ]);

        $session = ExamSession::query()->create([
            'user_id' => $user->id,
            'certification_level' => CertificationLevel::EMT_BASIC,
            'exam_type' => ExamSession::TYPE_MOCK,
            'current_difficulty' => 3,
            'ability_estimate' => 0.5,
            'expires_at' => now()->addHours(2),
            'status' => ExamSession::STATUS_IN_PROGRESS,
        ]);

        $this->actingAs($user)
            ->get("/emt-basic/mock-exam/{$session->id}")
            ->assertOk()
            ->assertSee('Time remaining')
            ->assertSee('Mock question two')
            ->assertDontSee('% correct');
    }

    public function test_mock_exam_terminates_with_pass_or_fail_outcome_only(): void
    {
        $user = User::factory()->create();

        $session = ExamSession::query()->create([
            'user_id' => $user->id,
            'certification_level' => CertificationLevel::EMT_BASIC,
            'exam_type' => ExamSession::TYPE_MOCK,
            'current_difficulty' => 4,
            'questions_answered' => 70,
            'correct_count' => 60,
            'ability_estimate' => 0.82,
            'expires_at' => now()->addHour(),
            'status' => ExamSession::STATUS_IN_PROGRESS,
        ]);

        app(MockExamService::class)->finalize($session, ExamSession::MOCK_PASS);

        $this->actingAs($user)
            ->get("/emt-basic/mock-exam/{$session->id}/outcome")
            ->assertOk()
            ->assertSee('Pass')
            ->assertDontSee('Answer review');
    }
}
