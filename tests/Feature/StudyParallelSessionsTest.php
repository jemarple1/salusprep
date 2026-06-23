<?php

namespace Tests\Feature;

use App\Models\ExamAnswer;
use App\Models\ExamSession;
use App\Models\Question;
use App\Models\StudySession;
use App\Services\GuestService;
use App\Services\PublicFlashcardDeckService;
use App\Support\CertificationLevel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class StudyParallelSessionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_start_second_deck_while_another_session_is_in_progress(): void
    {
        for ($index = 0; $index < 5; $index++) {
            Question::query()->create([
                'source_key' => 'parallel-public-'.$index,
                'certification_level' => CertificationLevel::EMT_BASIC,
                'category' => 'Airway',
                'difficulty' => 2,
                'initial_difficulty' => 2,
                'stem' => 'Question '.$index,
                'option_a' => 'Answer A',
                'option_b' => 'Answer B',
                'option_c' => 'Answer C',
                'option_d' => 'Answer D',
                'correct_option' => 'A',
                'explanation' => 'Because A.',
            ]);
        }

        $guestToken = (string) Str::uuid();

        StudySession::query()->create([
            'guest_token' => $guestToken,
            'certification_level' => CertificationLevel::EMT_BASIC,
            'public_deck_key' => PublicFlashcardDeckService::GENERAL_KEY,
            'deck' => [1, 2, 3],
            'initial_deck_size' => 3,
            'cards_studied' => 1,
            'status' => StudySession::STATUS_IN_PROGRESS,
        ]);

        $this->withSession([GuestService::SESSION_KEY => $guestToken])
            ->post('/emt-basic/study/public/start', [
                'deck_key' => 'Airway',
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->assertSame(2, StudySession::query()->where('status', StudySession::STATUS_IN_PROGRESS)->count());
    }

    public function test_study_index_shows_progress_on_active_deck_instead_of_session_banner(): void
    {
        for ($index = 0; $index < 5; $index++) {
            Question::query()->create([
                'source_key' => 'progress-public-'.$index,
                'certification_level' => CertificationLevel::EMT_BASIC,
                'category' => 'Airway',
                'difficulty' => 2,
                'initial_difficulty' => 2,
                'stem' => 'Question '.$index,
                'option_a' => 'Answer A',
                'option_b' => 'Answer B',
                'option_c' => 'Answer C',
                'option_d' => 'Answer D',
                'correct_option' => 'A',
                'explanation' => 'Because A.',
            ]);
        }

        $guestToken = (string) Str::uuid();

        $question = Question::query()->first();

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

        StudySession::query()->create([
            'guest_token' => $guestToken,
            'certification_level' => CertificationLevel::EMT_BASIC,
            'public_deck_key' => PublicFlashcardDeckService::GENERAL_KEY,
            'deck' => [$question->id, 2, 3],
            'initial_deck_size' => 3,
            'cards_studied' => 1,
            'status' => StudySession::STATUS_IN_PROGRESS,
        ]);

        $this->withSession([GuestService::SESSION_KEY => $guestToken])
            ->get('/emt-basic/study')
            ->assertOk()
            ->assertSee('Continue deck')
            ->assertSee('cards left')
            ->assertDontSee('Session in progress')
            ->assertDontSee('Finish your current flashcard session');
    }
}
