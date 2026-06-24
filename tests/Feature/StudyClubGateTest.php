<?php

namespace Tests\Feature;

use App\Models\GuestSectionProgress;
use App\Models\Question;
use App\Models\StudyClubMember;
use App\Models\StudySession;
use App\Services\GuestService;
use App\Services\PreviewAccessService;
use App\Support\CertificationLevel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Tests\TestCase;

class StudyClubGateTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['study_pass.enabled' => true]);
    }

    public function test_study_pass_gate_is_skipped_when_disabled(): void
    {
        config(['study_pass.enabled' => false]);

        Carbon::setTestNow('2026-06-01 12:00:00');

        $guestToken = (string) Str::uuid();
        $deviceId = (string) Str::uuid();
        $questions = collect([
            $this->createQuestion('disabled-1'),
            $this->createQuestion('disabled-2'),
            $this->createQuestion('disabled-3'),
            $this->createQuestion('disabled-4'),
        ]);

        $studySession = StudySession::query()->create([
            'guest_token' => $guestToken,
            'device_id' => $deviceId,
            'certification_level' => CertificationLevel::EMT_BASIC,
            'deck' => $questions->pluck('id')->all(),
            'initial_deck_size' => 4,
            'status' => StudySession::STATUS_IN_PROGRESS,
        ]);

        $session = [GuestService::SESSION_KEY => $guestToken];

        foreach ($questions as $question) {
            $this->withSession($session)
                ->withCookie(GuestService::DEVICE_COOKIE_KEY, $deviceId)
                ->post("/emt-basic/study/{$studySession->id}/advance", ['action' => 'strong'])
                ->assertRedirect()
                ->assertSessionMissing('study_club_required');

            $studySession->refresh();
        }

        Carbon::setTestNow();
    }

    public function test_first_three_preview_actions_are_allowed_without_study_pass(): void
    {
        Carbon::setTestNow('2026-06-01 12:00:00');

        $guestToken = (string) Str::uuid();
        $deviceId = (string) Str::uuid();
        $questions = collect([
            $this->createQuestion('pass-1'),
            $this->createQuestion('pass-2'),
            $this->createQuestion('pass-3'),
        ]);

        $studySession = StudySession::query()->create([
            'guest_token' => $guestToken,
            'device_id' => $deviceId,
            'certification_level' => CertificationLevel::EMT_BASIC,
            'deck' => $questions->pluck('id')->all(),
            'initial_deck_size' => 3,
            'status' => StudySession::STATUS_IN_PROGRESS,
        ]);

        $session = [GuestService::SESSION_KEY => $guestToken];

        foreach ($questions as $index => $question) {
            $this->withSession($session)
                ->withCookie(GuestService::DEVICE_COOKIE_KEY, $deviceId)
                ->post("/emt-basic/study/{$studySession->id}/advance", ['action' => 'strong'])
                ->assertRedirect()
                ->assertSessionMissing('study_club_required');

            $studySession->refresh();
        }

        $this->assertSame(0, StudyClubMember::query()->count());
        $this->assertSame(
            PreviewAccessService::STUDY_PASS_ACTIONS_REQUIRED,
            GuestSectionProgress::query()
                ->where('device_id', $deviceId)
                ->where('certification_level', CertificationLevel::EMT_BASIC)
                ->value('preview_actions_used'),
        );

        Carbon::setTestNow();
    }

    public function test_fourth_preview_action_requires_study_pass_membership(): void
    {
        Carbon::setTestNow('2026-06-01 12:00:00');

        $guestToken = (string) Str::uuid();
        $deviceId = (string) Str::uuid();
        $questions = collect([
            $this->createQuestion('gate-1'),
            $this->createQuestion('gate-2'),
            $this->createQuestion('gate-3'),
            $this->createQuestion('gate-4'),
        ]);

        $studySession = StudySession::query()->create([
            'guest_token' => $guestToken,
            'device_id' => $deviceId,
            'certification_level' => CertificationLevel::EMT_BASIC,
            'deck' => $questions->pluck('id')->all(),
            'initial_deck_size' => 4,
            'status' => StudySession::STATUS_IN_PROGRESS,
        ]);

        $session = [GuestService::SESSION_KEY => $guestToken];

        foreach ($questions->take(3) as $question) {
            $this->withSession($session)
                ->withCookie(GuestService::DEVICE_COOKIE_KEY, $deviceId)
                ->post("/emt-basic/study/{$studySession->id}/advance", ['action' => 'strong'])
                ->assertRedirect()
                ->assertSessionMissing('study_club_required');

            $studySession->refresh();
        }

        $this->withSession($session)
            ->withCookie(GuestService::DEVICE_COOKIE_KEY, $deviceId)
            ->post("/emt-basic/study/{$studySession->id}/advance", ['action' => 'strong'])
            ->assertRedirect()
            ->assertSessionHas('study_club_required');

        Carbon::setTestNow();
    }

    public function test_study_pass_join_unlocks_preview_actions(): void
    {
        Carbon::setTestNow('2026-06-01 12:00:00');

        $guestToken = (string) Str::uuid();
        $deviceId = (string) Str::uuid();
        $questions = collect([
            $this->createQuestion('join-1'),
            $this->createQuestion('join-2'),
            $this->createQuestion('join-3'),
            $this->createQuestion('join-4'),
        ]);

        $studySession = StudySession::query()->create([
            'guest_token' => $guestToken,
            'device_id' => $deviceId,
            'certification_level' => CertificationLevel::EMT_BASIC,
            'deck' => $questions->pluck('id')->all(),
            'initial_deck_size' => 4,
            'status' => StudySession::STATUS_IN_PROGRESS,
        ]);

        $session = [GuestService::SESSION_KEY => $guestToken];

        foreach ($questions->take(3) as $question) {
            $this->withSession($session)
                ->withCookie(GuestService::DEVICE_COOKIE_KEY, $deviceId)
                ->post("/emt-basic/study/{$studySession->id}/advance", ['action' => 'strong'])
                ->assertRedirect();

            $studySession->refresh();
        }

        $this->withSession($session)
            ->withCookie(GuestService::DEVICE_COOKIE_KEY, $deviceId)
            ->post('/emt-basic/study-club/join', ['email' => 'preview@example.com'])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->withSession($session)
            ->withCookie(GuestService::DEVICE_COOKIE_KEY, $deviceId)
            ->post("/emt-basic/study/{$studySession->id}/advance", ['action' => 'strong'])
            ->assertRedirect()
            ->assertSessionMissing('study_club_required');

        $this->assertDatabaseHas('study_club_members', [
            'email' => 'preview@example.com',
            'device_id' => $deviceId,
            'unsubscribed_at' => null,
        ]);

        Carbon::setTestNow();
    }

    public function test_study_pass_gate_shows_modal_after_three_actions(): void
    {
        Carbon::setTestNow('2026-06-01 12:00:00');

        $guestToken = (string) Str::uuid();
        $deviceId = (string) Str::uuid();

        GuestSectionProgress::query()->create([
            'device_id' => $deviceId,
            'guest_token' => $deviceId,
            'certification_level' => CertificationLevel::EMT_BASIC,
            'preview_actions_used' => PreviewAccessService::STUDY_PASS_ACTIONS_REQUIRED,
            'preview_started_at' => now(),
        ]);

        $this->withSession([GuestService::SESSION_KEY => $guestToken])
            ->withCookie(GuestService::DEVICE_COOKIE_KEY, $deviceId)
            ->get('/emt-basic/study')
            ->assertOk()
            ->assertSee('Join Study Pass for free')
            ->assertSee('Join Study Pass — continue free');

        Carbon::setTestNow();
    }

    public function test_study_pass_unsubscribe(): void
    {
        $member = StudyClubMember::query()->create([
            'email' => 'leave@example.com',
            'joined_at' => now(),
            'unsubscribe_token' => 'test-unsubscribe-token',
        ]);

        $this->get('/study-club/unsubscribe/test-unsubscribe-token')
            ->assertOk()
            ->assertSee('You&rsquo;re unsubscribed', false);

        $member->refresh();
        $this->assertNotNull($member->unsubscribed_at);
    }

    private function createQuestion(string $sourceKey = 'study-pass-q'): Question
    {
        return Question::query()->create([
            'source_key' => $sourceKey,
            'certification_level' => CertificationLevel::EMT_BASIC,
            'category' => 'Airway',
            'difficulty' => 2,
            'initial_difficulty' => 2,
            'stem' => 'Study Pass gate question',
            'option_a' => 'Answer A',
            'option_b' => 'Answer B',
            'option_c' => 'Answer C',
            'option_d' => 'Answer D',
            'correct_option' => 'A',
            'explanation' => 'Because A.',
        ]);
    }
}
