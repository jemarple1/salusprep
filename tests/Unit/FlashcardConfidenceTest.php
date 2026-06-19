<?php

namespace Tests\Unit;

use App\Models\FlashcardReview;
use App\Models\Question;
use App\Models\StudySession;
use App\Models\User;
use App\Services\StudyService;
use App\Support\CertificationLevel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FlashcardConfidenceTest extends TestCase
{
    use RefreshDatabase;

    private StudyService $study;

    protected function setUp(): void
    {
        parent::setUp();

        $this->study = app(StudyService::class);
    }

    public function test_weak_response_requeues_card_near_front_of_session_deck(): void
    {
        $user = User::factory()->create();
        $questions = $this->makeQuestions(4);
        $session = $this->sessionWithDeck($user, $questions);

        $this->study->advance($session, FlashcardReview::RESPONSE_WEAK, $user);

        $session->refresh();

        $this->assertSame(
            [$questions[1]->id, $questions[2]->id, $questions[0]->id, $questions[3]->id],
            $session->deck,
        );
        $this->assertSame(1, $session->cards_studied);
    }

    public function test_strong_response_removes_card_from_session_deck(): void
    {
        $user = User::factory()->create();
        $questions = $this->makeQuestions(3);
        $session = $this->sessionWithDeck($user, $questions);

        $this->study->advance($session, FlashcardReview::RESPONSE_STRONG, $user);

        $session->refresh();

        $this->assertSame([$questions[1]->id, $questions[2]->id], $session->deck);
    }

    public function test_weak_cards_sort_ahead_of_strong_cards_in_new_decks(): void
    {
        $user = User::factory()->create();
        $level = CertificationLevel::EMT_BASIC;
        $questions = $this->makeQuestions(4);

        $this->study->recordReview($user, $level, $questions[0]->id, FlashcardReview::RESPONSE_STRONG);
        $this->study->recordReview($user, $level, $questions[0]->id, FlashcardReview::RESPONSE_STRONG);
        $this->study->recordReview($user, $level, $questions[1]->id, FlashcardReview::RESPONSE_WEAK);
        $this->study->recordReview($user, $level, $questions[2]->id, FlashcardReview::RESPONSE_WEAK);
        $this->study->recordReview($user, $level, $questions[2]->id, FlashcardReview::RESPONSE_WEAK);

        $ordered = $this->study->orderDeckByConfidence($user, $level, collect($questions)->pluck('id')->all());

        $this->assertSame($questions[2]->id, $ordered[0]);
        $this->assertSame($questions[1]->id, $ordered[1]);
        $this->assertContains($questions[3]->id, array_slice($ordered, 2, 2));
        $this->assertContains($questions[0]->id, array_slice($ordered, 2, 2));
        $this->assertLessThan(
            FlashcardReview::query()->where('question_id', $questions[0]->id)->value('ease_score'),
            FlashcardReview::query()->where('question_id', $questions[2]->id)->value('ease_score'),
        );
    }

    public function test_legacy_review_and_mastered_actions_still_work(): void
    {
        $user = User::factory()->create();
        $questions = $this->makeQuestions(3);
        $session = $this->sessionWithDeck($user, $questions);

        $this->study->advance($session, 'review', $user);
        $session->refresh();
        $this->assertSame(
            [$questions[1]->id, $questions[2]->id, $questions[0]->id],
            $session->deck,
        );

        $this->study->advance($session->fresh(), 'mastered', $user);
        $session->refresh();
        $this->assertSame([$questions[2]->id, $questions[0]->id], $session->deck);
    }

    /** @return list<Question> */
    private function makeQuestions(int $count): array
    {
        $questions = [];

        for ($i = 0; $i < $count; $i++) {
            $questions[] = Question::query()->create([
                'source_key' => 'flashcard-test-'.uniqid(),
                'certification_level' => CertificationLevel::EMT_BASIC,
                'category' => 'Airway',
                'difficulty' => 2,
                'initial_difficulty' => 2,
                'stem' => 'Test question',
                'option_a' => 'Answer A',
                'option_b' => 'Answer B',
                'option_c' => 'Answer C',
                'option_d' => 'Answer D',
                'correct_option' => 'A',
                'explanation' => 'Test explanation.',
            ]);
        }

        return $questions;
    }

    /** @param list<Question> $questions */
    private function sessionWithDeck(User $user, array $questions): StudySession
    {
        $deck = collect($questions)->pluck('id')->all();

        return StudySession::create([
            'user_id' => $user->id,
            'certification_level' => CertificationLevel::EMT_BASIC,
            'deck' => $deck,
            'initial_deck_size' => count($deck),
            'status' => StudySession::STATUS_IN_PROGRESS,
        ]);
    }
}
