<?php

namespace Tests\Feature;

use App\Models\ExamSession;
use App\Models\Question;
use App\Models\User;
use App\Services\AdaptiveExamService;
use App\Support\CertificationLevel;
use App\Support\WelcomeReturn;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WelcomeReturnLinkTest extends TestCase
{
    use RefreshDatabase;

    /** @return list<Question> */
    private function seedQuestions(int $count = 5): array
    {
        $questions = [];

        for ($i = 1; $i <= $count; $i++) {
            $questions[] = Question::query()->create([
                'source_key' => "welcome-return-test-{$i}",
                'certification_level' => CertificationLevel::EMT_BASIC,
                'category' => 'Airway',
                'difficulty' => 2,
                'initial_difficulty' => 2,
                'stem' => "Question {$i}",
                'option_a' => 'Answer A',
                'option_b' => 'Answer B',
                'option_c' => 'Answer C',
                'option_d' => 'Answer D',
                'correct_option' => 'A',
                'explanation' => 'Because A is correct.',
            ]);
        }

        return $questions;
    }

    public function test_skill_exercise_shows_return_link_when_opened_from_welcome(): void
    {
        $user = User::factory()->create();
        app(AdaptiveExamService::class)->unlockSection($user, CertificationLevel::EMT_BASIC);

        $this->actingAs($user)
            ->get('/emt-basic/exercises/soap-charting?from=welcome')
            ->assertOk()
            ->assertSee("Return to today's checklist", false);
    }

    public function test_skill_exercise_hides_return_link_without_welcome_context(): void
    {
        $user = User::factory()->create();
        app(AdaptiveExamService::class)->unlockSection($user, CertificationLevel::EMT_BASIC);

        $this->actingAs($user)
            ->get('/emt-basic/exercises/soap-charting')
            ->assertOk()
            ->assertDontSee("Return to today's checklist", false);
    }

    public function test_exam_show_displays_return_link_after_starting_from_welcome(): void
    {
        $this->seedQuestions();

        $user = User::factory()->create();
        app(AdaptiveExamService::class)->unlockSection($user, CertificationLevel::EMT_BASIC);

        $this->actingAs($user)
            ->post('/emt-basic/exam/start', ['from' => 'welcome'])
            ->assertRedirect();

        $session = ExamSession::query()->where('user_id', $user->id)->first();
        $this->assertNotNull($session);

        $this->actingAs($user)
            ->get(route('exam.show', ['emt-basic', $session]))
            ->assertOk()
            ->assertSee("Return to today's checklist", false);
    }

    public function test_visiting_welcome_clears_return_link_state(): void
    {
        $user = User::factory()->create();
        app(AdaptiveExamService::class)->unlockSection($user, CertificationLevel::EMT_BASIC);

        $this->actingAs($user)
            ->get('/emt-basic/exercises/soap-charting?from=welcome')
            ->assertOk()
            ->assertSee("Return to today's checklist", false);

        $this->actingAs($user)
            ->get('/emt-basic/welcome')
            ->assertOk();

        $this->actingAs($user)
            ->get('/emt-basic/exercises/soap-charting')
            ->assertOk()
            ->assertDontSee("Return to today's checklist", false);
    }

    public function test_welcome_checklist_links_include_from_welcome_param(): void
    {
        $user = User::factory()->create();
        app(AdaptiveExamService::class)->unlockSection($user, CertificationLevel::EMT_BASIC);

        $this->actingAs($user)
            ->get('/emt-basic/welcome')
            ->assertOk()
            ->assertSee(WelcomeReturn::QUERY_PARAM.'='.WelcomeReturn::QUERY_VALUE, false);
    }
}
