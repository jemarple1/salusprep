<?php

namespace Tests\Feature;

use App\Models\ExamSession;
use App\Models\Question;
use App\Models\User;
use App\Support\CertificationLevel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlatformHomeTest extends TestCase
{
    use RefreshDatabase;

    /** @return list<Question> */
    private function seedQuestions(int $count = 5): array
    {
        $questions = [];

        for ($i = 1; $i <= $count; $i++) {
            $questions[] = Question::query()->create([
                'source_key' => "home-test-{$i}",
                'certification_level' => CertificationLevel::EMT_BASIC,
                'category' => 'Airway',
                'difficulty' => 2,
                'initial_difficulty' => 2,
                'stem' => "Landing preview question {$i}",
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

    public function test_home_shows_features_and_mock_exam_button(): void
    {
        $this->seedQuestions();

        $this->get('/emt-basic')
            ->assertOk()
            ->assertSee('Try the real mock exam')
            ->assertSee('once per day')
            ->assertDontSee('Use the ⛨ menu')
            ->assertSee('25-question focus')
            ->assertSee('Skill exercises')
            ->assertDontSee('Choose a focus exam')
            ->assertDontSee('Resume quiz')
            ->assertDontSee('Your EMT-Basic access');
    }

    public function test_home_shows_preview_question_on_right(): void
    {
        $this->seedQuestions();

        $this->get('/emt-basic')
            ->assertOk()
            ->assertSee('Start a practice quiz')
            ->assertSee('Question 1 of 25')
            ->assertSee('Tap an answer to start your quiz')
            ->assertDontSee('Submit answer');
    }

    public function test_preview_answer_starts_quiz_and_shows_review_for_question_one(): void
    {
        $questions = $this->seedQuestions();
        $question = $questions[0];

        $response = $this->post('/emt-basic/exam/preview-answer', [
            'question_id' => $question->id,
            'selected_option' => 'A',
        ]);

        $session = ExamSession::query()->first();
        $this->assertNotNull($session);
        $this->assertSame(1, $session->questions_answered);

        $response
            ->assertRedirect(route('exam.show', ['emt-basic', $session]))
            ->assertSessionMissing('exam.reviewed_answer_id.'.$session->id);

        $this->get(route('exam.show', ['emt-basic', $session]))
            ->assertOk()
            ->assertSee('Question 1 of 25')
            ->assertSee('Correct')
            ->assertSee('Because A is correct.');
    }
}
