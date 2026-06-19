<?php

namespace Tests\Unit;

use App\Models\ExamSession;
use App\Models\Question;
use App\Models\User;
use App\Services\AdaptiveExamService;
use App\Support\CertificationLevel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class FocusCategoryExamTest extends TestCase
{
    use RefreshDatabase;

    public function test_quiz_length_is_twenty_five_questions(): void
    {
        $this->assertSame(25, CertificationLevel::QUIZ_QUESTIONS);
    }

    public function test_focus_exam_weights_majority_of_questions_to_focus_category(): void
    {
        $user = User::factory()->create();

        foreach (['Airway', 'Cardiology', 'Trauma'] as $category) {
            for ($i = 1; $i <= 30; $i++) {
                Question::query()->create([
                    'source_key' => "focus-test-{$category}-{$i}",
                    'certification_level' => CertificationLevel::EMT_BASIC,
                    'category' => $category,
                    'difficulty' => 2,
                    'initial_difficulty' => 2,
                    'stem' => "Test question {$category} {$i}",
                    'option_a' => 'Answer A',
                    'option_b' => 'Answer B',
                    'option_c' => 'Answer C',
                    'option_d' => 'Answer D',
                    'correct_option' => 'A',
                    'explanation' => 'Test explanation.',
                ]);
            }
        }

        $session = ExamSession::create([
            'user_id' => $user->id,
            'certification_level' => CertificationLevel::EMT_BASIC,
            'focus_category' => 'Airway',
            'current_difficulty' => 2,
            'status' => ExamSession::STATUS_IN_PROGRESS,
        ]);

        $service = app(AdaptiveExamService::class);
        $focusCount = 0;

        for ($i = 0; $i < 25; $i++) {
            $question = $service->nextQuestion($session);

            $this->assertNotNull($question);

            $request = Request::create('/', 'POST');
            $request->setLaravelSession($this->app['session.store']);
            $request->setUserResolver(fn () => $user);

            $service->submitAnswer(
                $request,
                $session->fresh(),
                $question,
                $question->correct_option,
            );

            $session->refresh();

            if ($question->category === 'Airway') {
                $focusCount++;
            }
        }

        $this->assertSame(25, $session->questions_answered);
        $this->assertGreaterThanOrEqual(17, $focusCount);
    }
}
