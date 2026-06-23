<?php

namespace Tests\Feature;

use App\Models\ExamSession;
use App\Models\ExerciseScenarioCompletion;
use App\Models\SectionAccess;
use App\Models\User;
use App\Services\AdaptiveExamService;
use App\Services\WelcomeDailyPlanService;
use App\Support\CertificationLevel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class PlatformWelcomeTest extends TestCase
{
    use RefreshDatabase;

    public function test_unlocked_user_can_view_welcome_page(): void
    {
        $user = User::factory()->create();
        app(AdaptiveExamService::class)->unlockSection($user, CertificationLevel::EMT_BASIC);

        $this->actingAs($user)
            ->get('/emt-basic/welcome')
            ->assertOk()
            ->assertSee('Full Access unlocked')
            ->assertSee('Your next steps')
            ->assertSee('study checklist', false);
    }

    public function test_unlocked_user_without_exam_date_sees_checklist_nav_link(): void
    {
        $user = User::factory()->create();
        app(AdaptiveExamService::class)->unlockSection($user, CertificationLevel::EMT_BASIC);

        $this->actingAs($user)
            ->get('/emt-basic/welcome')
            ->assertOk()
            ->assertSee('Open your daily study checklist', false)
            ->assertSee('>Today<', false);
    }

    public function test_unlocked_user_with_exam_date_sees_calendar_nav_not_checklist(): void
    {
        $user = User::factory()->create();
        app(AdaptiveExamService::class)->unlockSection($user, CertificationLevel::EMT_BASIC);

        SectionAccess::query()
            ->where('user_id', $user->id)
            ->where('certification_level', CertificationLevel::EMT_BASIC)
            ->update(['exam_date' => now()->addDays(30)]);

        $this->actingAs($user)
            ->get('/emt-basic/welcome')
            ->assertOk()
            ->assertSee('30 days', false)
            ->assertDontSee('Open your daily study checklist', false);
    }

    public function test_day_one_welcome_points_to_nav_button_for_returning(): void
    {
        $user = User::factory()->create(['name' => 'Alex Student']);
        app(AdaptiveExamService::class)->unlockSection($user, CertificationLevel::EMT_BASIC);

        $this->actingAs($user)
            ->get('/emt-basic/welcome')
            ->assertOk()
            ->assertSee('start today\'s plan', false)
            ->assertSee('checklist button', false)
            ->assertSee('come back here tomorrow', false);
    }

    public function test_locked_user_is_redirected_from_welcome_page(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/emt-basic/welcome')
            ->assertRedirect(route('platform.paywall', 'emt-basic'));
    }

    public function test_user_can_save_exam_date_and_see_countdown_in_navbar(): void
    {
        $user = User::factory()->create();
        app(AdaptiveExamService::class)->unlockSection($user, CertificationLevel::EMT_BASIC);

        $examDate = now()->addDays(30)->toDateString();

        $this->actingAs($user)
            ->post('/emt-basic/welcome/exam-date', ['exam_date' => $examDate])
            ->assertRedirect(route('platform.welcome', 'emt-basic'));

        $this->assertSame(
            $examDate,
            SectionAccess::query()
                ->where('user_id', $user->id)
                ->where('certification_level', CertificationLevel::EMT_BASIC)
                ->value('exam_date')
                ?->toDateString(),
        );

        $this->actingAs($user)
            ->get('/emt-basic/welcome')
            ->assertOk()
            ->assertSee('30 days', false)
            ->assertDontSee('When is your exam?');
    }

    public function test_user_can_update_exam_date_from_settings(): void
    {
        $user = User::factory()->create();
        app(AdaptiveExamService::class)->unlockSection($user, CertificationLevel::EMT_BASIC);

        $examDate = now()->addDays(45)->toDateString();

        $this->actingAs($user)
            ->put('/settings/exam-date/emt-basic', ['exam_date' => $examDate])
            ->assertRedirect(route('settings.edit'))
            ->assertSessionHas('success');

        $this->assertSame(
            $examDate,
            SectionAccess::query()
                ->where('user_id', $user->id)
                ->where('certification_level', CertificationLevel::EMT_BASIC)
                ->value('exam_date')
                ?->toDateString(),
        );
    }

    public function test_user_can_clear_exam_date_from_settings(): void
    {
        $user = User::factory()->create();
        app(AdaptiveExamService::class)->unlockSection($user, CertificationLevel::EMT_BASIC);

        SectionAccess::query()
            ->where('user_id', $user->id)
            ->where('certification_level', CertificationLevel::EMT_BASIC)
            ->update(['exam_date' => now()->addDays(10)]);

        $this->actingAs($user)
            ->put('/settings/exam-date/emt-basic', ['exam_date' => ''])
            ->assertRedirect(route('settings.edit'))
            ->assertSessionHas('success');

        $this->assertNull(
            SectionAccess::query()
                ->where('user_id', $user->id)
                ->where('certification_level', CertificationLevel::EMT_BASIC)
                ->value('exam_date'),
        );
    }

    public function test_cleared_exam_date_shows_card_on_welcome_again(): void
    {
        $user = User::factory()->create();
        app(AdaptiveExamService::class)->unlockSection($user, CertificationLevel::EMT_BASIC);

        SectionAccess::query()
            ->where('user_id', $user->id)
            ->where('certification_level', CertificationLevel::EMT_BASIC)
            ->update(['exam_date' => now()->addDays(10)]);

        $this->actingAs($user)
            ->get('/emt-basic/welcome')
            ->assertOk()
            ->assertDontSee('When is your exam?');

        $this->actingAs($user)
            ->put('/settings/exam-date/emt-basic', ['exam_date' => '']);

        $this->actingAs($user)
            ->get('/emt-basic/welcome')
            ->assertOk()
            ->assertSee('When is your exam?');
    }

    public function test_mock_checkout_tracks_google_ads_purchase_conversion_once(): void
    {
        $user = User::factory()->create();
        app(AdaptiveExamService::class)->unlockSection($user, CertificationLevel::EMT_BASIC);

        $this->actingAs($user)
            ->withSession(['track_purchase_conversion' => true])
            ->get('/emt-basic/welcome')
            ->assertOk()
            ->assertSee('AW-18250454039/JED9COKs58EcEJeov_5D', false);

        $this->actingAs($user)
            ->get('/emt-basic/welcome')
            ->assertOk()
            ->assertDontSee('AW-18250454039/JED9COKs58EcEJeov_5D', false);
    }

    public function test_welcome_shows_daily_checklist_after_first_day(): void
    {
        Carbon::setTestNow('2026-06-11 10:00:00');

        $user = User::factory()->create();
        app(AdaptiveExamService::class)->unlockSection($user, CertificationLevel::EMT_BASIC);

        SectionAccess::query()
            ->where('user_id', $user->id)
            ->where('certification_level', CertificationLevel::EMT_BASIC)
            ->update(['unlocked_at' => now()->subDay()]);

        $this->actingAs($user)
            ->get('/emt-basic/welcome')
            ->assertOk()
            ->assertSee('study checklist', false)
            ->assertSee('Daily progress')
            ->assertSee('Adaptive quiz 1')
            ->assertSee('Daily mock exam')
            ->assertSee('Welcome back', false)
            ->assertDontSee('Step 1 · Test your knowledge');
    }

    public function test_welcome_shows_daily_checklist_on_unlock_day(): void
    {
        Carbon::setTestNow('2026-06-11 10:00:00');

        $user = User::factory()->create();
        app(AdaptiveExamService::class)->unlockSection($user, CertificationLevel::EMT_BASIC);

        $this->actingAs($user)
            ->get('/emt-basic/welcome')
            ->assertOk()
            ->assertSee('study checklist', false)
            ->assertSee('Adaptive quiz 1')
            ->assertSee('Your next steps')
            ->assertSee('Step 1 · Test your knowledge');

        Carbon::setTestNow();
    }

    public function test_daily_checklist_marks_completed_skill_and_updates_progress(): void
    {
        Carbon::setTestNow('2026-06-11 10:00:00');

        $user = User::factory()->create();
        app(AdaptiveExamService::class)->unlockSection($user, CertificationLevel::EMT_BASIC);

        SectionAccess::query()
            ->where('user_id', $user->id)
            ->where('certification_level', CertificationLevel::EMT_BASIC)
            ->update(['unlocked_at' => now()->subDay()]);

        $recommended = app(WelcomeDailyPlanService::class)
            ->recommendedSkills(CertificationLevel::EMT_BASIC, 1);

        ExerciseScenarioCompletion::query()->create([
            'user_id' => $user->id,
            'certification_level' => CertificationLevel::EMT_BASIC,
            'exercise_slug' => $recommended[0]['slug'],
            'exercise_level' => 1,
            'scenario_index' => 0,
            'completed_at' => now(),
        ]);

        ExamSession::query()->create([
            'user_id' => $user->id,
            'certification_level' => CertificationLevel::EMT_BASIC,
            'current_difficulty' => 3,
            'questions_answered' => 25,
            'status' => ExamSession::STATUS_COMPLETED,
            'completed_at' => now(),
        ]);

        $this->actingAs($user)
            ->get('/emt-basic/welcome')
            ->assertOk()
            ->assertSee($recommended[0]['title'])
            ->assertSee('2/6');

        Carbon::setTestNow();
    }

    public function test_stripe_return_tracks_conversion_with_session_id(): void
    {
        $user = User::factory()->create();
        app(AdaptiveExamService::class)->unlockSection($user, CertificationLevel::EMT_BASIC);

        $this->actingAs($user)
            ->get('/emt-basic/welcome?session_id=cs_test_123')
            ->assertOk()
            ->assertSee('cs_test_123', false);

        $this->actingAs($user)
            ->get('/emt-basic/welcome?session_id=cs_test_123')
            ->assertOk()
            ->assertDontSee("gtag('event', 'conversion'", false);
    }
}
