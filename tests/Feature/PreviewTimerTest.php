<?php

namespace Tests\Feature;

use App\Models\ExamAnswer;
use App\Models\ExamSession;
use App\Models\Question;
use App\Models\User;
use App\Services\GuestService;
use App\Support\CertificationLevel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Tests\TestCase;

class PreviewTimerTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_shows_preview_timer_for_active_guest(): void
    {
        Carbon::setTestNow('2026-06-01 12:00:00');

        $this->get('/emt-basic')
            ->assertOk()
            ->assertSee('id="preview-timer"', false)
            ->assertSee('◷');

        Carbon::setTestNow();
    }

    public function test_paywall_is_available_during_active_preview(): void
    {
        Carbon::setTestNow('2026-06-01 12:00:00');

        $this->get('/emt-basic/unlock')
            ->assertOk()
            ->assertSee('Keep the momentum going')
            ->assertSee('Preview ends in')
            ->assertSee('free preview time on SalusPrep')
            ->assertSee('Small steps, every day', false)
            ->assertSee('Daily progress', false)
            ->assertSee('Adaptive quiz 1', false);

        Carbon::setTestNow();
    }

    public function test_paywall_shows_focus_area_subtitle_when_quiz_data_exists(): void
    {
        Carbon::setTestNow('2026-06-01 12:00:00');

        $guestToken = (string) Str::uuid();
        $question = Question::query()->create([
            'source_key' => 'paywall-focus-1',
            'certification_level' => CertificationLevel::EMT_BASIC,
            'category' => 'Airway',
            'difficulty' => 2,
            'initial_difficulty' => 2,
            'stem' => 'Paywall focus question',
            'option_a' => 'Answer A',
            'option_b' => 'Answer B',
            'option_c' => 'Answer C',
            'option_d' => 'Answer D',
            'correct_option' => 'A',
            'explanation' => 'Because A.',
        ]);

        $examSession = ExamSession::query()->create([
            'guest_token' => $guestToken,
            'certification_level' => CertificationLevel::EMT_BASIC,
            'current_difficulty' => 2,
            'status' => ExamSession::STATUS_IN_PROGRESS,
        ]);

        ExamAnswer::query()->create([
            'exam_session_id' => $examSession->id,
            'question_id' => $question->id,
            'selected_option' => 'B',
            'is_correct' => false,
            'answered_at' => now(),
        ]);

        $this->withSession([GuestService::SESSION_KEY => $guestToken])
            ->get('/emt-basic/unlock')
            ->assertOk()
            ->assertSee('Keep the momentum going')
            ->assertSee('Start with Airway');

        Carbon::setTestNow();
    }

    public function test_unlocked_user_timer_links_to_welcome_page(): void
    {
        $user = User::factory()->create();
        $user->sectionAccesses()->create([
            'certification_level' => CertificationLevel::EMT_BASIC,
            'unlocked_at' => now(),
        ]);

        $this->actingAs($user)
            ->get('/emt-basic')
            ->assertOk()
            ->assertSee(route('platform.welcome', 'emt-basic'), false)
            ->assertSee('✓');
    }
}
