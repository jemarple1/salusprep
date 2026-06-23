<?php

namespace Tests\Unit;

use App\Models\ExamAnswer;
use App\Models\ExamSession;
use App\Models\Question;
use App\Support\CertificationLevel;
use App\Support\ExamReviewRecommendations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExamReviewRecommendationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_suggests_exercises_for_weak_categories(): void
    {
        $session = ExamSession::query()->create([
            'certification_level' => CertificationLevel::EMT_BASIC,
            'current_difficulty' => 2,
            'questions_answered' => 2,
            'correct_count' => 0,
            'status' => ExamSession::STATUS_COMPLETED,
        ]);

        $question = Question::query()->create([
            'source_key' => 'review-rec-1',
            'certification_level' => CertificationLevel::EMT_BASIC,
            'category' => 'Pharmacology',
            'difficulty' => 2,
            'initial_difficulty' => 2,
            'stem' => 'Medication question',
            'option_a' => 'A',
            'option_b' => 'B',
            'option_c' => 'C',
            'option_d' => 'D',
            'correct_option' => 'A',
            'explanation' => 'Because A.',
        ]);

        ExamAnswer::query()->create([
            'exam_session_id' => $session->id,
            'question_id' => $question->id,
            'selected_option' => 'B',
            'is_correct' => false,
            'answered_at' => now(),
        ]);

        $session->load(['answers.question']);

        $weak = ExamReviewRecommendations::weakCategories($session);
        $suggestions = ExamReviewRecommendations::suggestedExercises(
            CertificationLevel::EMT_BASIC,
            $weak,
        );

        $this->assertSame(['Pharmacology' => 1], $weak->all());
        $this->assertNotEmpty($suggestions);
        $this->assertSame('Pharmacology', $suggestions[0]['matched_category']);
        $this->assertSame('pharma-contraindications', $suggestions[0]['exercise']['slug']);
    }

    public function test_focus_option_targets_weakest_category_from_session(): void
    {
        $session = ExamSession::query()->create([
            'certification_level' => CertificationLevel::EMT_BASIC,
            'current_difficulty' => 2,
            'questions_answered' => 3,
            'correct_count' => 1,
            'status' => ExamSession::STATUS_COMPLETED,
        ]);

        $pharma = Question::query()->create([
            'source_key' => 'focus-rec-pharma-1',
            'certification_level' => CertificationLevel::EMT_BASIC,
            'category' => 'Pharmacology',
            'difficulty' => 2,
            'initial_difficulty' => 2,
            'stem' => 'Pharma 1',
            'option_a' => 'A',
            'option_b' => 'B',
            'option_c' => 'C',
            'option_d' => 'D',
            'correct_option' => 'A',
        ]);

        $pharmaTwo = Question::query()->create([
            'source_key' => 'focus-rec-pharma-2',
            'certification_level' => CertificationLevel::EMT_BASIC,
            'category' => 'Pharmacology',
            'difficulty' => 2,
            'initial_difficulty' => 2,
            'stem' => 'Pharma 2',
            'option_a' => 'A',
            'option_b' => 'B',
            'option_c' => 'C',
            'option_d' => 'D',
            'correct_option' => 'A',
        ]);

        $airway = Question::query()->create([
            'source_key' => 'focus-rec-airway-1',
            'certification_level' => CertificationLevel::EMT_BASIC,
            'category' => 'Airway',
            'difficulty' => 2,
            'initial_difficulty' => 2,
            'stem' => 'Airway 1',
            'option_a' => 'A',
            'option_b' => 'B',
            'option_c' => 'C',
            'option_d' => 'D',
            'correct_option' => 'A',
        ]);

        foreach ([[$pharma, 'B'], [$pharmaTwo, 'B'], [$airway, 'A']] as [$question, $selected]) {
            ExamAnswer::query()->create([
                'exam_session_id' => $session->id,
                'question_id' => $question->id,
                'selected_option' => $selected,
                'is_correct' => $selected === $question->correct_option,
                'answered_at' => now(),
            ]);
        }

        $session->load(['answers.question']);

        $focus = ExamReviewRecommendations::focusOptionForSession($session);

        $this->assertFalse($focus->is_general);
        $this->assertSame('Pharmacology', $focus->category);
        $this->assertSame(2, $focus->miss_count);
        $this->assertSame(0, $focus->accuracy_percent);
    }

    public function test_focus_option_is_null_when_quiz_is_perfect(): void
    {
        $session = ExamSession::query()->create([
            'certification_level' => CertificationLevel::EMT_BASIC,
            'current_difficulty' => 2,
            'questions_answered' => 1,
            'correct_count' => 1,
            'status' => ExamSession::STATUS_COMPLETED,
        ]);

        $question = Question::query()->create([
            'source_key' => 'focus-rec-perfect',
            'certification_level' => CertificationLevel::EMT_BASIC,
            'category' => 'Airway',
            'difficulty' => 2,
            'initial_difficulty' => 2,
            'stem' => 'Airway',
            'option_a' => 'A',
            'option_b' => 'B',
            'option_c' => 'C',
            'option_d' => 'D',
            'correct_option' => 'A',
        ]);

        ExamAnswer::query()->create([
            'exam_session_id' => $session->id,
            'question_id' => $question->id,
            'selected_option' => 'A',
            'is_correct' => true,
            'answered_at' => now(),
        ]);

        $session->load(['answers.question']);

        $this->assertNull(ExamReviewRecommendations::focusOptionForSession($session));
        $this->assertTrue(ExamReviewRecommendations::generalExamOption($session)->is_general);
    }
}
