<?php

namespace Tests\Feature;

use App\Mail\DailyStudyPlanMail;
use App\Models\User;
use App\Services\AdaptiveExamService;
use App\Support\CertificationLevel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SendDailyStudyEmailsCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_command_sends_to_unlocked_opted_in_users(): void
    {
        Mail::fake();
        Carbon::setTestNow('2026-06-12 09:00:00', 'America/New_York');

        $user = User::factory()->create(['daily_study_email_opt_in' => true]);
        app(AdaptiveExamService::class)->unlockSection($user, CertificationLevel::EMT_BASIC);

        $this->artisan('study:send-daily-emails')
            ->assertSuccessful();

        Mail::assertSent(DailyStudyPlanMail::class, 1);

        Carbon::setTestNow();
    }

    public function test_command_skips_opted_out_users(): void
    {
        Mail::fake();

        $user = User::factory()->create(['daily_study_email_opt_in' => false]);
        app(AdaptiveExamService::class)->unlockSection($user, CertificationLevel::EMT_BASIC);

        $this->artisan('study:send-daily-emails')
            ->assertSuccessful();

        Mail::assertNothingSent();
    }

    public function test_send_daily_test_email_command(): void
    {
        Mail::fake();

        $user = User::factory()->create(['daily_study_email_opt_in' => false]);
        app(AdaptiveExamService::class)->unlockSection($user, CertificationLevel::EMT_BASIC);

        $this->artisan('study:send-daily-test-email', ['--email' => $user->email])
            ->assertSuccessful();

        Mail::assertSent(DailyStudyPlanMail::class, function (DailyStudyPlanMail $mail) use ($user) {
            return $mail->hasTo($user->email)
                && str_starts_with($mail->payload['subject'], '[Test]');
        });
    }
}
