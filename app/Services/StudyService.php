<?php

namespace App\Services;

use App\Models\ExamAnswer;
use App\Models\Question;
use App\Models\StudySession;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class StudyService
{
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

    public function activeSession(User $user, string $certificationLevel): ?StudySession
    {
        return StudySession::query()
            ->where('user_id', $user->id)
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

        shuffle($deck);

        StudySession::query()
            ->where('user_id', $user->id)
            ->where('certification_level', $certificationLevel)
            ->where('status', StudySession::STATUS_IN_PROGRESS)
            ->update([
                'status' => StudySession::STATUS_COMPLETED,
                'completed_at' => now(),
            ]);

        return StudySession::create([
            'user_id' => $user->id,
            'certification_level' => $certificationLevel,
            'filter_category' => $category,
            'deck' => $deck,
            'initial_deck_size' => count($deck),
            'status' => StudySession::STATUS_IN_PROGRESS,
        ]);
    }

    public function advance(StudySession $session, string $action): void
    {
        $deck = $session->deck ?? [];

        if ($deck === []) {
            return;
        }

        $current = array_shift($deck);

        if ($action === 'review') {
            $deck[] = $current;
        }

        $session->cards_studied++;
        $session->deck = array_values($deck);

        if ($session->deck === []) {
            $session->status = StudySession::STATUS_COMPLETED;
            $session->completed_at = now();
        }

        $session->save();
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
