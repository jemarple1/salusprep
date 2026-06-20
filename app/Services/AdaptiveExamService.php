<?php

namespace App\Services;

use App\Models\ExamAnswer;
use App\Models\ExamSession;
use App\Models\Question;
use App\Models\SectionAccess;
use App\Models\User;
use App\Support\CertificationLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class AdaptiveExamService
{
    public function __construct(
        private GuestService $guests,
        private PreviewAccessService $preview,
        private FocusCategoryService $focusCategory,
    ) {}

    public function sectionAccess(User $user, string $certificationLevel): SectionAccess
    {
        return SectionAccess::firstOrCreate(
            [
                'user_id' => $user->id,
                'certification_level' => $certificationLevel,
            ],
            ['preview_actions_used' => 0],
        );
    }

    public function startSession(Request $request, string $certificationLevel, ?string $focusCategory = null): ExamSession
    {
        if (! CertificationLevel::isValid($certificationLevel)) {
            throw new RuntimeException('Invalid certification level.');
        }

        if ($this->preview->requiresPaywall($request, $certificationLevel)) {
            throw new RuntimeException('Unlock this section to start a new quiz.');
        }

        if ($focusCategory === null) {
            $focusCategory = $this->focusCategory->get($request, $certificationLevel);
        }

        if ($focusCategory !== null && ! $this->focusCategory->isValidCategory($certificationLevel, $focusCategory)) {
            $focusCategory = null;
        }

        if (Question::query()->where('certification_level', $certificationLevel)->doesntExist()) {
            $message = app()->environment('local')
                ? 'No questions are loaded for this platform. Run php artisan db:seed in your local project.'
                : 'No questions are available for this platform yet. Please try again later.';

            throw new RuntimeException($message);
        }

        $user = $request->user();

        if ($user !== null) {
            return $this->startUserSession($user, $certificationLevel, $focusCategory);
        }

        return $this->startGuestSession($request, $certificationLevel, $focusCategory);
    }

    private function startUserSession(User $user, string $certificationLevel, ?string $focusCategory): ExamSession
    {
        $existing = $user->activeExamSession($certificationLevel);

        if ($existing !== null) {
            throw new RuntimeException('Finish or continue your current quiz before starting a new one.');
        }

        return ExamSession::create([
            'user_id' => $user->id,
            'certification_level' => $certificationLevel,
            'focus_category' => $focusCategory,
            'current_difficulty' => 2,
            'status' => ExamSession::STATUS_IN_PROGRESS,
        ]);
    }

    private function startGuestSession(Request $request, string $certificationLevel, ?string $focusCategory): ExamSession
    {
        $guestToken = $this->guests->token($request);

        $existing = $this->guests->activeExamSession($guestToken, $certificationLevel);

        if ($existing !== null) {
            throw new RuntimeException('Finish or continue your current quiz before starting a new one.');
        }

        return ExamSession::create([
            'guest_token' => $guestToken,
            'certification_level' => $certificationLevel,
            'focus_category' => $focusCategory,
            'current_difficulty' => 2,
            'status' => ExamSession::STATUS_IN_PROGRESS,
        ]);
    }

    public function nextQuestion(ExamSession $session): ?Question
    {
        if ($session->isComplete()) {
            return null;
        }

        if ($session->hasReachedQuestionLimit()) {
            return null;
        }

        $answeredIds = $session->answers()->pluck('question_id');
        $pickFocus = $session->hasFocusCategory() && $this->shouldPickFocusCategory($session);

        $question = $this->pickQuestion(
            $session,
            $answeredIds,
            $pickFocus ? $session->focus_category : null,
        );

        if ($question !== null) {
            return $question;
        }

        if ($pickFocus) {
            $question = $this->pickQuestion($session, $answeredIds, null);

            if ($question !== null) {
                return $question;
            }
        }

        return $this->pickQuestion($session, $answeredIds, null, strictCategory: false);
    }

    /** @param  \Illuminate\Support\Collection<int, int>|list<int>  $answeredIds */
    private function pickQuestion(
        ExamSession $session,
        $answeredIds,
        ?string $category,
        bool $strictCategory = true,
    ): ?Question {
        $query = Question::query()
            ->where('certification_level', $session->certification_level)
            ->where('difficulty', $session->current_difficulty)
            ->whereNotIn('id', $answeredIds);

        if ($category !== null) {
            $query->where('category', $category);
        } elseif ($strictCategory && $session->hasFocusCategory()) {
            $query->where('category', '!=', $session->focus_category);
        }

        $question = $query->inRandomOrder()->first();

        if ($question !== null) {
            return $question;
        }

        $fallback = Question::query()
            ->where('certification_level', $session->certification_level)
            ->whereNotIn('id', $answeredIds);

        if ($category !== null) {
            $fallback->where('category', $category);
        } elseif ($strictCategory && $session->hasFocusCategory()) {
            $fallback->where('category', '!=', $session->focus_category);
        }

        return $fallback
            ->orderByRaw('ABS(difficulty - ?)', [$session->current_difficulty])
            ->inRandomOrder()
            ->first();
    }

    public function landingPreviewQuestion(string $certificationLevel, ?string $focusCategory = null): ?Question
    {
        if (Question::query()->where('certification_level', $certificationLevel)->doesntExist()) {
            return null;
        }

        $query = Question::query()
            ->where('certification_level', $certificationLevel)
            ->where('difficulty', 2);

        if ($focusCategory !== null) {
            $query->where('category', $focusCategory);
        }

        $question = $query->inRandomOrder()->first();

        if ($question !== null) {
            return $question;
        }

        $fallback = Question::query()
            ->where('certification_level', $certificationLevel);

        if ($focusCategory !== null) {
            $fallback->where('category', $focusCategory);
        }

        return $fallback->inRandomOrder()->first();
    }

    private function shouldPickFocusCategory(ExamSession $session): bool
    {
        $target = $session->targetQuestionCount();
        $focusQuota = (int) round($target * (CertificationLevel::FOCUS_CATEGORY_PERCENT / 100));
        $nonFocusQuota = $target - $focusQuota;

        $focusAnswered = $session->answers()
            ->whereHas('question', fn ($query) => $query->where('category', $session->focus_category))
            ->count();

        $answered = $session->questions_answered;
        $nonFocusAnswered = $answered - $focusAnswered;
        $remaining = $target - $answered;
        $focusRemaining = $focusQuota - $focusAnswered;

        if ($focusRemaining <= 0) {
            return false;
        }

        if ($nonFocusAnswered >= $nonFocusQuota) {
            return true;
        }

        if ($focusRemaining >= $remaining) {
            return true;
        }

        return random_int(1, 100) <= CertificationLevel::FOCUS_CATEGORY_PERCENT;
    }

    public function submitAnswer(Request $request, ExamSession $session, Question $question, string $selectedOption): ExamAnswer
    {
        if ($session->isComplete()) {
            throw new RuntimeException('This exam session cannot accept answers.');
        }

        if ($session->answers()->where('question_id', $question->id)->exists()) {
            throw new RuntimeException('This question has already been answered.');
        }

        $selectedOption = strtoupper($selectedOption);
        $isCorrect = $selectedOption === strtoupper($question->correct_option);

        return DB::transaction(function () use ($request, $session, $question, $selectedOption, $isCorrect) {
            $answer = ExamAnswer::create([
                'exam_session_id' => $session->id,
                'question_id' => $question->id,
                'selected_option' => $selectedOption,
                'is_correct' => $isCorrect,
                'answered_at' => now(),
            ]);

            $session->questions_answered++;
            if ($isCorrect) {
                $session->correct_count++;
                $session->current_difficulty = min(5, $session->current_difficulty + 1);
            } else {
                $session->current_difficulty = max(1, $session->current_difficulty - 1);
            }

            if (! $session->sectionIsUnlocked()) {
                $this->preview->recordAction($request, $session->certification_level);
            }

            if ($session->hasReachedQuestionLimit()) {
                $session->status = ExamSession::STATUS_COMPLETED;
                $session->completed_at = now();
            }

            $session->save();

            return $answer;
        });
    }

    public function completeSession(ExamSession $session): ExamSession
    {
        $session->update([
            'status' => ExamSession::STATUS_COMPLETED,
            'completed_at' => now(),
        ]);

        return $session->fresh();
    }

    public function unlockSection(User $user, string $certificationLevel): SectionAccess
    {
        $access = $this->sectionAccess($user, $certificationLevel);
        $access->update(['unlocked_at' => now()]);

        return $access->fresh();
    }
}
