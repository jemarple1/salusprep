<?php

namespace Tests\Feature;

use App\Models\ExamAnswer;
use App\Models\ExamSession;
use App\Models\Question;
use App\Services\GuestService;
use App\Support\CertificationLevel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ExamResultsReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_results_page_shows_review_deck_exercise_suggestions_and_answer_list(): void
    {
        $guestToken = (string) Str::uuid();

        $session = ExamSession::query()->create([
            'guest_token' => $guestToken,
            'certification_level' => CertificationLevel::EMT_BASIC,
            'current_difficulty' => 2,
            'questions_answered' => 2,
            'correct_count' => 1,
            'status' => ExamSession::STATUS_COMPLETED,
        ]);

        $wrongQuestion = Question::query()->create([
            'source_key' => 'results-review-wrong',
            'certification_level' => CertificationLevel::EMT_BASIC,
            'category' => 'Pharmacology',
            'difficulty' => 2,
            'initial_difficulty' => 2,
            'stem' => 'Which medication is contraindicated?',
            'option_a' => 'Epinephrine',
            'option_b' => 'Nitroglycerin',
            'option_c' => 'Albuterol',
            'option_d' => 'Aspirin',
            'correct_option' => 'A',
            'explanation' => 'Epinephrine is indicated here.',
        ]);

        $rightQuestion = Question::query()->create([
            'source_key' => 'results-review-right',
            'certification_level' => CertificationLevel::EMT_BASIC,
            'category' => 'Airway',
            'difficulty' => 2,
            'initial_difficulty' => 2,
            'stem' => 'Best airway maneuver?',
            'option_a' => 'Head-tilt chin-lift',
            'option_b' => 'Chest compressions',
            'option_c' => 'Defibrillation',
            'option_d' => 'Tourniquet',
            'correct_option' => 'A',
            'explanation' => 'Open the airway first.',
        ]);

        ExamAnswer::query()->create([
            'exam_session_id' => $session->id,
            'question_id' => $wrongQuestion->id,
            'selected_option' => 'B',
            'is_correct' => false,
            'answered_at' => now(),
        ]);

        ExamAnswer::query()->create([
            'exam_session_id' => $session->id,
            'question_id' => $rightQuestion->id,
            'selected_option' => 'A',
            'is_correct' => true,
            'answered_at' => now(),
        ]);

        $this->withSession([GuestService::SESSION_KEY => $guestToken])
            ->get(route('exam.results', ['emt-basic', $session]))
            ->assertOk()
            ->assertSee('Review deck')
            ->assertSee('Practice before your next quiz')
            ->assertSee('Answer review')
            ->assertSee('Which medication is contraindicated?')
            ->assertSee('Best airway maneuver?');
    }
}
