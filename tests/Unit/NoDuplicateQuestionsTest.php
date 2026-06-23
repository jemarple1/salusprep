<?php

namespace Tests\Unit;

use App\Models\ExamAnswer;
use App\Models\ExamSession;
use App\Models\Question;
use App\Models\User;
use App\Services\AdaptiveExamService;
use App\Support\CertificationLevel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Tests\TestCase;

class NoDuplicateQuestionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_never_sees_the_same_question_twice_across_quizzes(): void
    {
        $user = User::factory()->create();

        $questions = collect(range(1, 4))->map(function (int $index) {
            return Question::query()->create([
                'source_key' => "unique-q-{$index}",
                'certification_level' => CertificationLevel::EMT_BASIC,
                'category' => 'Airway',
                'difficulty' => 2,
                'initial_difficulty' => 2,
                'stem' => "Unique question {$index}",
                'option_a' => 'A',
                'option_b' => 'B',
                'option_c' => 'C',
                'option_d' => 'D',
                'correct_option' => 'A',
            ]);
        });

        $service = app(AdaptiveExamService::class);
        $request = Request::create('/', 'POST');
        $request->setLaravelSession($this->app['session.store']);
        $request->setUserResolver(fn () => $user);

        $firstSession = ExamSession::create([
            'user_id' => $user->id,
            'certification_level' => CertificationLevel::EMT_BASIC,
            'current_difficulty' => 2,
            'status' => ExamSession::STATUS_IN_PROGRESS,
        ]);

        $seenInFirstQuiz = [];

        for ($i = 0; $i < 2; $i++) {
            $question = $service->nextQuestion($firstSession->fresh());
            $this->assertNotNull($question);
            $seenInFirstQuiz[] = $question->id;
            $service->submitAnswer($request, $firstSession->fresh(), $question, 'A');
            $firstSession->refresh();
        }

        $service->completeSession($firstSession->fresh());

        $secondSession = ExamSession::create([
            'user_id' => $user->id,
            'certification_level' => CertificationLevel::EMT_BASIC,
            'current_difficulty' => 2,
            'status' => ExamSession::STATUS_IN_PROGRESS,
        ]);

        for ($i = 0; $i < 2; $i++) {
            $question = $service->nextQuestion($secondSession->fresh());
            $this->assertNotNull($question);
            $this->assertNotContains($question->id, $seenInFirstQuiz);
            $service->submitAnswer($request, $secondSession->fresh(), $question, 'A');
            $secondSession->refresh();
        }

        $this->assertSame(
            4,
            ExamAnswer::query()
                ->whereIn('question_id', $questions->pluck('id'))
                ->distinct('question_id')
                ->count('question_id'),
        );
    }

    public function test_guest_never_sees_the_same_question_twice_across_quizzes(): void
    {
        $guestToken = (string) Str::uuid();

        foreach (range(1, 3) as $index) {
            Question::query()->create([
                'source_key' => "guest-unique-q-{$index}",
                'certification_level' => CertificationLevel::EMT_BASIC,
                'category' => 'Medical',
                'difficulty' => 2,
                'initial_difficulty' => 2,
                'stem' => "Guest question {$index}",
                'option_a' => 'A',
                'option_b' => 'B',
                'option_c' => 'C',
                'option_d' => 'D',
                'correct_option' => 'A',
            ]);
        }

        $service = app(AdaptiveExamService::class);
        $request = Request::create('/', 'POST');
        $request->setLaravelSession($this->app['session.store']);
        $request->session()->put(\App\Services\GuestService::SESSION_KEY, $guestToken);

        $firstSeen = null;

        $firstSession = ExamSession::create([
            'guest_token' => $guestToken,
            'certification_level' => CertificationLevel::EMT_BASIC,
            'current_difficulty' => 2,
            'status' => ExamSession::STATUS_IN_PROGRESS,
        ]);

        $firstQuestion = $service->nextQuestion($firstSession);
        $this->assertNotNull($firstQuestion);
        $firstSeen = $firstQuestion->id;
        $service->submitAnswer($request, $firstSession->fresh(), $firstQuestion, 'A');

        $secondSession = ExamSession::create([
            'guest_token' => $guestToken,
            'certification_level' => CertificationLevel::EMT_BASIC,
            'current_difficulty' => 2,
            'status' => ExamSession::STATUS_IN_PROGRESS,
        ]);

        $secondQuestion = $service->nextQuestion($secondSession);
        $this->assertNotNull($secondQuestion);
        $this->assertNotSame($firstSeen, $secondQuestion->id);
    }
}
