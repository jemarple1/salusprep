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
}
