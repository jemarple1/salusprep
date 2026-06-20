<?php

namespace Tests\Feature;

use App\Models\ExamAnswer;
use App\Models\ExamSession;
use App\Models\Question;
use App\Models\StudySession;
use App\Services\GuestService;
use App\Support\CertificationLevel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class GuestStudyTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_and_start_flashcards_without_login(): void
    {
        $guestToken = (string) Str::uuid();
        $question = Question::query()->create([
            'source_key' => 'guest-study-1',
            'certification_level' => CertificationLevel::EMT_BASIC,
            'category' => 'Airway',
            'difficulty' => 2,
            'initial_difficulty' => 2,
            'stem' => 'Guest flashcard question',
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
            ->get('/emt-basic/study')
            ->assertOk()
            ->assertSee('1 cards ready')
            ->assertDontSee('Sign in to use flashcards');

        $response = $this->withSession([GuestService::SESSION_KEY => $guestToken])
            ->post('/emt-basic/study/start');

        $studySession = StudySession::query()->first();
        $this->assertNotNull($studySession);
        $this->assertNull($studySession->user_id);
        $this->assertSame($guestToken, $studySession->guest_token);

        $response
            ->assertRedirect(route('study.show', ['emt-basic', $studySession]))
            ->assertSessionHasNoErrors();
    }
}
