<?php

namespace App\Services;

use App\Models\ExamAnswer;
use App\Models\FlashcardReview;
use App\Models\Question;
use App\Models\StudySession;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class StudyService
{
    /** Lower ease scores surface first in new decks. */
    private const EASE_WEAK_DELTA = -2;

    private const EASE_STRONG_DELTA = 3;

    /** Re-queue weak cards a few positions ahead so they return soon in the same session. */
    private const WEAK_REQUEUE_POSITION = 2;
    /** @return list<int> Question IDs a guest most recently answered incorrectly. */
    public function wrongQuestionIdsForGuest(string $guestToken, string $certificationLevel, ?string $category = null): array
    {
        $latestPerQuestion = DB::table('exam_answers')
            ->join('exam_sessions', 'exam_answers.exam_session_id', '=', 'exam_sessions.id')
            ->where('exam_sessions.guest_token', $guestToken)
            ->whereNull('exam_sessions.user_id')
            ->where('exam_sessions.certification_level', $certificationLevel)
            ->groupBy('exam_answers.question_id')
            ->select([
                'exam_answers.question_id',
                DB::raw('MAX(exam_answers.answered_at) as latest_answered_at'),
            ]);

        $query = DB::table('exam_answers')
            ->join('exam_sessions', 'exam_answers.exam_session_id', '=', 'exam_sessions.id')
            ->join('questions', 'exam_answers.question_id', '=', 'questions.id')
            ->joinSub($latestPerQuestion, 'latest', function ($join) {
                $join->on('exam_answers.question_id', '=', 'latest.question_id')
                    ->on('exam_answers.answered_at', '=', 'latest.latest_answered_at');
            })
            ->where('exam_sessions.guest_token', $guestToken)
            ->whereNull('exam_sessions.user_id')
            ->where('exam_sessions.certification_level', $certificationLevel)
            ->where('exam_answers.is_correct', false);

        if ($category !== null) {
            $query->where('questions.category', $category);
        }

        return $query
            ->pluck('exam_answers.question_id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    /**
     * @return Collection<int, object{question: Question, wrong_option: ?string}>
     */
    public function previewMissedQuestions(
        ?User $user,
        ?string $guestToken,
        string $certificationLevel,
        int $limit = 4,
    ): Collection {
        $ids = $user !== null
            ? $this->wrongQuestionIds($user, $certificationLevel)
            : ($guestToken !== null ? $this->wrongQuestionIdsForGuest($guestToken, $certificationLevel) : []);

        if ($ids === []) {
            return collect();
        }

        return $this->questionsForIds(array_slice($ids, 0, $limit))
            ->map(function (Question $question) use ($user, $guestToken, $certificationLevel) {
                $wrongOption = null;

                if ($user !== null) {
                    $wrongOption = $this->lastWrongAnswer($user, $certificationLevel, $question)?->selected_option;
                } elseif ($guestToken !== null) {
                    $wrongOption = ExamAnswer::query()
                        ->whereHas('session', function ($query) use ($guestToken, $certificationLevel) {
                            $query->where('guest_token', $guestToken)
                                ->whereNull('user_id')
                                ->where('certification_level', $certificationLevel);
                        })
                        ->where('question_id', $question->id)
                        ->where('is_correct', false)
                        ->latest('answered_at')
                        ->value('selected_option');
                }

                return (object) [
                    'question' => $question,
                    'wrong_option' => $wrongOption,
                ];
            });
    }

    /** @return array<string, int> */
    public function wrongCountsByCategoryForGuest(string $guestToken, string $certificationLevel): array
    {
        $ids = $this->wrongQuestionIdsForGuest($guestToken, $certificationLevel);

        if ($ids === []) {
            return [];
        }

        return Question::query()
            ->whereIn('id', $ids)
            ->select('category', DB::raw('COUNT(*) as count'))
            ->groupBy('category')
            ->orderBy('category')
            ->pluck('count', 'category')
            ->map(fn ($count) => (int) $count)
            ->all();
    }

    public function activeAllDeckSession(User $user, string $certificationLevel): ?StudySession
    {
        return StudySession::query()
            ->where('user_id', $user->id)
            ->where('certification_level', $certificationLevel)
            ->whereNull('filter_category')
            ->whereNull('public_deck_key')
            ->where('status', StudySession::STATUS_IN_PROGRESS)
            ->latest()
            ->first();
    }

    public function activeAllDeckSessionForGuest(string $guestToken, string $certificationLevel): ?StudySession
    {
        return StudySession::query()
            ->where('guest_token', $guestToken)
            ->whereNull('user_id')
            ->where('certification_level', $certificationLevel)
            ->whereNull('filter_category')
            ->whereNull('public_deck_key')
            ->where('status', StudySession::STATUS_IN_PROGRESS)
            ->latest()
            ->first();
    }

    /** @return list<int> Question IDs the user most recently answered incorrectly. */
    public function wrongQuestionIds(User $user, string $certificationLevel, ?string $category = null): array
    {
        $latestPerQuestion = DB::table('exam_answers')
            ->join('exam_sessions', 'exam_answers.exam_session_id', '=', 'exam_sessions.id')
            ->where('exam_sessions.user_id', $user->id)
            ->where('exam_sessions.certification_level', $certificationLevel)
            ->groupBy('exam_answers.question_id')
            ->select([
                'exam_answers.question_id',
                DB::raw('MAX(exam_answers.answered_at) as latest_answered_at'),
            ]);

        $query = DB::table('exam_answers')
            ->join('exam_sessions', 'exam_answers.exam_session_id', '=', 'exam_sessions.id')
            ->join('questions', 'exam_answers.question_id', '=', 'questions.id')
            ->joinSub($latestPerQuestion, 'latest', function ($join) {
                $join->on('exam_answers.question_id', '=', 'latest.question_id')
                    ->on('exam_answers.answered_at', '=', 'latest.latest_answered_at');
            })
            ->where('exam_sessions.user_id', $user->id)
            ->where('exam_sessions.certification_level', $certificationLevel)
            ->where('exam_answers.is_correct', false);

        if ($category !== null) {
            $query->where('questions.category', $category);
        }

        return $query
            ->pluck('exam_answers.question_id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    /** @return array<string, int> */
    public function wrongCountsByCategory(User $user, string $certificationLevel): array
    {
        $ids = $this->wrongQuestionIds($user, $certificationLevel);

        if ($ids === []) {
            return [];
        }

        return Question::query()
            ->whereIn('id', $ids)
            ->select('category', DB::raw('COUNT(*) as count'))
            ->groupBy('category')
            ->orderBy('category')
            ->pluck('count', 'category')
            ->map(fn ($count) => (int) $count)
            ->all();
    }

    /** @return \Illuminate\Support\Collection<int, StudySession> */
    public function activeSessions(User $user, string $certificationLevel): \Illuminate\Support\Collection
    {
        return StudySession::query()
            ->where('user_id', $user->id)
            ->where('certification_level', $certificationLevel)
            ->where('status', StudySession::STATUS_IN_PROGRESS)
            ->latest()
            ->get();
    }

    /** @return \Illuminate\Support\Collection<int, StudySession> */
    public function activeSessionsForGuest(string $guestToken, string $certificationLevel): \Illuminate\Support\Collection
    {
        return StudySession::query()
            ->where('guest_token', $guestToken)
            ->whereNull('user_id')
            ->where('certification_level', $certificationLevel)
            ->where('status', StudySession::STATUS_IN_PROGRESS)
            ->latest()
            ->get();
    }

    public function activePublicSession(User $user, string $certificationLevel, string $deckKey): ?StudySession
    {
        return StudySession::query()
            ->where('user_id', $user->id)
            ->where('certification_level', $certificationLevel)
            ->where('public_deck_key', $deckKey)
            ->where('status', StudySession::STATUS_IN_PROGRESS)
            ->latest()
            ->first();
    }

    public function activePublicSessionForGuest(string $guestToken, string $certificationLevel, string $deckKey): ?StudySession
    {
        return StudySession::query()
            ->where('guest_token', $guestToken)
            ->whereNull('user_id')
            ->where('certification_level', $certificationLevel)
            ->where('public_deck_key', $deckKey)
            ->where('status', StudySession::STATUS_IN_PROGRESS)
            ->latest()
            ->first();
    }

    public function activePersonalSession(User $user, string $certificationLevel, ?string $category = null): ?StudySession
    {
        return StudySession::query()
            ->where('user_id', $user->id)
            ->where('certification_level', $certificationLevel)
            ->whereNull('public_deck_key')
            ->when(
                $category === null,
                fn ($query) => $query->whereNull('filter_category'),
                fn ($query) => $query->where('filter_category', $category),
            )
            ->where('status', StudySession::STATUS_IN_PROGRESS)
            ->latest()
            ->first();
    }

    public function activePersonalSessionForGuest(string $guestToken, string $certificationLevel, ?string $category = null): ?StudySession
    {
        return StudySession::query()
            ->where('guest_token', $guestToken)
            ->whereNull('user_id')
            ->where('certification_level', $certificationLevel)
            ->whereNull('public_deck_key')
            ->when(
                $category === null,
                fn ($query) => $query->whereNull('filter_category'),
                fn ($query) => $query->where('filter_category', $category),
            )
            ->where('status', StudySession::STATUS_IN_PROGRESS)
            ->latest()
            ->first();
    }

    public function activeSession(User $user, string $certificationLevel): ?StudySession
    {
        return StudySession::query()
            ->where('user_id', $user->id)
            ->where('certification_level', $certificationLevel)
            ->where('status', StudySession::STATUS_IN_PROGRESS)
            ->latest()
            ->first();
    }

    public function activeSessionForGuest(string $guestToken, string $certificationLevel): ?StudySession
    {
        return StudySession::query()
            ->where('guest_token', $guestToken)
            ->whereNull('user_id')
            ->where('certification_level', $certificationLevel)
            ->where('status', StudySession::STATUS_IN_PROGRESS)
            ->latest()
            ->first();
    }

    public function startSession(User $user, string $certificationLevel, ?string $category = null): StudySession
    {
        $deck = $this->wrongQuestionIds($user, $certificationLevel, $category);

        if ($deck === []) {
            throw new RuntimeException('No missed questions to review yet. Complete a quiz first.');
        }

        $deck = $this->orderDeckByConfidence($user, $certificationLevel, $deck);

        return StudySession::create([
            'user_id' => $user->id,
            'certification_level' => $certificationLevel,
            'filter_category' => $category,
            'deck' => $deck,
            'initial_deck_size' => count($deck),
            'status' => StudySession::STATUS_IN_PROGRESS,
        ]);
    }

    public function startSessionForGuest(string $guestToken, string $certificationLevel, ?string $category = null, ?string $deviceId = null): StudySession
    {
        $deck = $this->wrongQuestionIdsForGuest($guestToken, $certificationLevel, $category);

        if ($deck === []) {
            throw new RuntimeException('No missed questions to review yet. Complete a quiz first.');
        }

        shuffle($deck);

        return StudySession::create([
            'guest_token' => $guestToken,
            'device_id' => $deviceId,
            'certification_level' => $certificationLevel,
            'filter_category' => $category,
            'deck' => $deck,
            'initial_deck_size' => count($deck),
            'status' => StudySession::STATUS_IN_PROGRESS,
        ]);
    }

    public function startPublicSession(User $user, string $certificationLevel, string $deckKey): StudySession
    {
        $category = $this->publicDeckCategory($deckKey);
        $deck = $this->publicQuestionIds($certificationLevel, $category);

        if ($deck === []) {
            throw new RuntimeException('This flashcard deck is not available yet.');
        }

        shuffle($deck);

        return StudySession::create([
            'user_id' => $user->id,
            'certification_level' => $certificationLevel,
            'filter_category' => $category,
            'public_deck_key' => $deckKey,
            'deck' => $deck,
            'initial_deck_size' => count($deck),
            'status' => StudySession::STATUS_IN_PROGRESS,
        ]);
    }

    public function startPublicSessionForGuest(
        string $guestToken,
        string $certificationLevel,
        string $deckKey,
        ?string $deviceId = null,
    ): StudySession {
        $category = $this->publicDeckCategory($deckKey);
        $deck = $this->publicQuestionIds($certificationLevel, $category);

        if ($deck === []) {
            throw new RuntimeException('This flashcard deck is not available yet.');
        }

        shuffle($deck);

        return StudySession::create([
            'guest_token' => $guestToken,
            'device_id' => $deviceId,
            'certification_level' => $certificationLevel,
            'filter_category' => $category,
            'public_deck_key' => $deckKey,
            'deck' => $deck,
            'initial_deck_size' => count($deck),
            'status' => StudySession::STATUS_IN_PROGRESS,
        ]);
    }

    /** @return list<int> */
    public function publicQuestionIds(string $certificationLevel, ?string $category = null, ?int $limit = null): array
    {
        $limit ??= PublicFlashcardDeckService::DECK_SIZE;

        $query = Question::query()->where('certification_level', $certificationLevel);

        if ($category !== null) {
            $query->where('category', $category);
        }

        return $query
            ->inRandomOrder()
            ->limit($limit)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    private function publicDeckCategory(string $deckKey): ?string
    {
        return $deckKey === PublicFlashcardDeckService::GENERAL_KEY ? null : $deckKey;
    }

    public function advance(StudySession $session, string $action, User $user): void
    {
        $deck = $session->deck ?? [];

        if ($deck === []) {
            return;
        }

        $current = array_shift($deck);
        $response = $this->normalizeAdvanceAction($action);

        $this->recordReview($user, $session->certification_level, $current, $response);

        if ($response === FlashcardReview::RESPONSE_WEAK) {
            $position = min(self::WEAK_REQUEUE_POSITION, count($deck));
            array_splice($deck, $position, 0, [$current]);
        }

        $session->cards_studied++;
        $session->deck = array_values($deck);

        if ($session->deck === []) {
            $session->status = StudySession::STATUS_COMPLETED;
            $session->completed_at = now();
        }

        $session->save();
    }

    public function advanceGuest(StudySession $session, string $action): void
    {
        $deck = $session->deck ?? [];

        if ($deck === []) {
            return;
        }

        $current = array_shift($deck);
        $response = $this->normalizeAdvanceAction($action);

        if ($response === FlashcardReview::RESPONSE_WEAK) {
            $position = min(self::WEAK_REQUEUE_POSITION, count($deck));
            array_splice($deck, $position, 0, [$current]);
        }

        $session->cards_studied++;
        $session->deck = array_values($deck);

        if ($session->deck === []) {
            $session->status = StudySession::STATUS_COMPLETED;
            $session->completed_at = now();
        }

        $session->save();
    }

    public function recordReview(User $user, string $certificationLevel, int $questionId, string $response): FlashcardReview
    {
        $review = FlashcardReview::query()->firstOrNew([
            'user_id' => $user->id,
            'question_id' => $questionId,
            'certification_level' => $certificationLevel,
        ]);

        if (! $review->exists) {
            $review->ease_score = 0;
            $review->times_weak = 0;
            $review->times_strong = 0;
        }

        if ($response === FlashcardReview::RESPONSE_WEAK) {
            $review->ease_score += self::EASE_WEAK_DELTA;
            $review->times_weak++;
        } else {
            $review->ease_score += self::EASE_STRONG_DELTA;
            $review->times_strong++;
        }

        $review->last_reviewed_at = now();
        $review->save();

        return $review;
    }

    /** @param list<int> $questionIds */
    public function orderDeckByConfidence(User $user, string $certificationLevel, array $questionIds): array
    {
        if ($questionIds === []) {
            return [];
        }

        $scores = FlashcardReview::query()
            ->where('user_id', $user->id)
            ->where('certification_level', $certificationLevel)
            ->whereIn('question_id', $questionIds)
            ->pluck('ease_score', 'question_id');

        $grouped = [];

        foreach ($questionIds as $id) {
            $score = (int) ($scores[$id] ?? 0);
            $grouped[$score][] = $id;
        }

        ksort($grouped);

        $ordered = [];

        foreach ($grouped as $group) {
            shuffle($group);
            $ordered = array_merge($ordered, $group);
        }

        return $ordered;
    }

    private function normalizeAdvanceAction(string $action): string
    {
        return match ($action) {
            FlashcardReview::RESPONSE_WEAK, 'review' => FlashcardReview::RESPONSE_WEAK,
            FlashcardReview::RESPONSE_STRONG, 'mastered' => FlashcardReview::RESPONSE_STRONG,
            default => throw new RuntimeException("Unknown flashcard advance action: {$action}"),
        };
    }

    public function lastWrongAnswer(User $user, string $certificationLevel, Question $question): ?ExamAnswer
    {
        return ExamAnswer::query()
            ->whereHas('session', function ($query) use ($user, $certificationLevel) {
                $query->where('user_id', $user->id)
                    ->where('certification_level', $certificationLevel);
            })
            ->where('question_id', $question->id)
            ->where('is_correct', false)
            ->latest('answered_at')
            ->first();
    }

    public function lastWrongAnswerForGuest(string $guestToken, string $certificationLevel, Question $question): ?ExamAnswer
    {
        return ExamAnswer::query()
            ->whereHas('session', function ($query) use ($guestToken, $certificationLevel) {
                $query->where('guest_token', $guestToken)
                    ->whereNull('user_id')
                    ->where('certification_level', $certificationLevel);
            })
            ->where('question_id', $question->id)
            ->where('is_correct', false)
            ->latest('answered_at')
            ->first();
    }

    /** @return Collection<int, Question> */
    public function questionsForIds(array $ids): Collection
    {
        if ($ids === []) {
            return collect();
        }

        return Question::query()
            ->whereIn('id', $ids)
            ->get()
            ->sortBy(fn (Question $q) => array_search($q->id, $ids, true))
            ->values();
    }
}
