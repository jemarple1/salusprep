<?php

namespace Tests\Unit;

use App\Mail\DailyStudyPlanMail;
use App\Models\ExamAnswer;
use App\Models\ExamSession;
use App\Models\ExerciseScenarioCompletion;
use App\Models\Question;
use App\Models\SectionAccess;
use App\Models\User;
use App\Services\AdaptiveExamService;
use App\Services\DailyStudyEmailService;
use App\Support\CertificationLevel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class DailyStudyEmailServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_sends_daily_email_with_checklist_skill_spotlight_and_review_fact(): void
    {
        Mail::fake();
        Carbon::setTestNow('2026-06-12 09:00:00', 'America/New_York');

        $user = User::factory()->create(['daily_study_email_opt_in' => true]);
        app(AdaptiveExamService::class)->unlockSection($user, CertificationLevel::EMT_BASIC);

        $access = SectionAccess::query()
            ->where('user_id', $user->id)
            ->where('certification_level', CertificationLevel::EMT_BASIC)
            ->first();

        $access->update(['exam_date' => now()->addDays(20)]);

        $question = Question::query()->create([
            'source_key' => 'daily-email-fact',
            'certification_level' => CertificationLevel::EMT_BASIC,
            'category' => 'Airway',
            'difficulty' => 2,
            'initial_difficulty' => 2,
            'stem' => 'Which action comes first for a complete airway obstruction?',
            'option_a' => 'Back blows',
            'option_b' => 'Heimlich maneuver',
            'option_c' => 'Finger sweep',
            'option_d' => 'Oropharyngeal airway',
            'correct_option' => 'B',
            'explanation' => 'Abdominal thrusts are first for conscious complete obstruction in adults.',
        ]);

        $session = ExamSession::query()->create([
            'user_id' => $user->id,
            'certification_level' => CertificationLevel::EMT_BASIC,
            'current_difficulty' => 3,
            'questions_answered' => 1,
            'status' => ExamSession::STATUS_COMPLETED,
            'completed_at' => now()->subDay(),
        ]);

        ExamAnswer::query()->create([
            'exam_session_id' => $session->id,
            'question_id' => $question->id,
            'selected_option' => 'A',
            'is_correct' => false,
            'answered_at' => now()->subDay(),
        ]);

        $service = app(DailyStudyEmailService::class);
        $payload = $service->buildPayload($user, $access->fresh());

        $this->assertCount(6, $payload['items']);
        $this->assertNotNull($payload['featuredSkill']);
        $this->assertTrue($payload['reviewFact']['hasMiss']);
        $this->assertStringContainsString('Abdominal thrusts', $payload['reviewFact']['body']);

        $this->assertTrue($service->send($access->fresh(), $user));

        Mail::assertSent(DailyStudyPlanMail::class, function (DailyStudyPlanMail $mail) use ($user) {
            return $mail->hasTo($user->email)
                && str_contains($mail->payload['subject'], 'checklist');
        });

        $this->assertSame(
            '2026-06-12',
            $access->fresh()->last_daily_study_email_sent_on?->toDateString(),
        );

        Carbon::setTestNow();
    }

    public function test_does_not_send_on_exam_day_or_after(): void
    {
        Mail::fake();
        Carbon::setTestNow('2026-06-20 09:00:00', 'America/New_York');

        $user = User::factory()->create(['daily_study_email_opt_in' => true]);
        app(AdaptiveExamService::class)->unlockSection($user, CertificationLevel::EMT_BASIC);

        $access = SectionAccess::query()
            ->where('user_id', $user->id)
            ->where('certification_level', CertificationLevel::EMT_BASIC)
            ->first();

        $access->update(['exam_date' => '2026-06-20']);

        $service = app(DailyStudyEmailService::class);

        $this->assertFalse($service->shouldSend($access->fresh(), $user));
        $this->assertFalse($service->send($access->fresh(), $user));

        Mail::assertNothingSent();

        Carbon::setTestNow();
    }

    public function test_featured_skill_prefers_uncovered_exercises(): void
    {
        $user = User::factory()->create();
        app(AdaptiveExamService::class)->unlockSection($user, CertificationLevel::EMT_BASIC);

        $access = SectionAccess::query()
            ->where('user_id', $user->id)
            ->where('certification_level', CertificationLevel::EMT_BASIC)
            ->first();

        ExerciseScenarioCompletion::query()->create([
            'user_id' => $user->id,
            'certification_level' => CertificationLevel::EMT_BASIC,
            'exercise_slug' => 'soap-charting',
            'exercise_level' => 1,
            'scenario_index' => 0,
            'completed_at' => now(),
        ]);

        $service = app(DailyStudyEmailService::class);
        $skill = $service->featuredUncoveredSkill($user, CertificationLevel::EMT_BASIC, 1);

        $this->assertNotNull($skill);
        $this->assertNotSame('soap-charting', $skill['title']);
        $this->assertTrue($skill['uncovered']);
    }

    public function test_signed_unsubscribe_url_opt_out(): void
    {
        $user = User::factory()->create(['daily_study_email_opt_in' => true]);

        $url = URL::signedRoute('email.daily-study.unsubscribe', ['user' => $user->id]);

        $this->get($url)
            ->assertOk()
            ->assertSee('unsubscribed', false);

        $this->assertFalse($user->fresh()->daily_study_email_opt_in);
    }
}
