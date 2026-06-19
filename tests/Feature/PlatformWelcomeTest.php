<?php

namespace Tests\Feature;

use App\Models\SectionAccess;
use App\Models\User;
use App\Services\AdaptiveExamService;
use App\Support\CertificationLevel;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
            ->assertSee('Sharpen your skills');
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
            ->assertSee('30 days');
    }

    public function test_user_can_clear_exam_date(): void
    {
        $user = User::factory()->create();
        app(AdaptiveExamService::class)->unlockSection($user, CertificationLevel::EMT_BASIC);

        SectionAccess::query()
            ->where('user_id', $user->id)
            ->where('certification_level', CertificationLevel::EMT_BASIC)
            ->update(['exam_date' => now()->addDays(10)]);

        $this->actingAs($user)
            ->post('/emt-basic/welcome/exam-date', ['exam_date' => ''])
            ->assertRedirect(route('platform.welcome', 'emt-basic'));

        $this->assertNull(
            SectionAccess::query()
                ->where('user_id', $user->id)
                ->where('certification_level', CertificationLevel::EMT_BASIC)
                ->value('exam_date'),
        );
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
