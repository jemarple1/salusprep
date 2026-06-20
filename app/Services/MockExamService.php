<?php

namespace App\Services;

use App\Models\ExamSession;
use App\Models\Question;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class MockExamService
{
    public const MIN_QUESTIONS = 70;

    public const MAX_QUESTIONS = 140;

    public const TIME_LIMIT_SECONDS = 7200;

    public const PASS_ABILITY = 0.58;

    public const FAIL_ABILITY = 0.42;

    public const EARLY_PASS_ABILITY = 0.78;

    public const EARLY_FAIL_ABILITY = 0.22;

    public function __construct(
        private AdaptiveExamService $examService,
        private PreviewAccessService $preview,
    ) {}

    public function canStartToday(User $user, string $certificationLevel): bool
    {
        if ($this->activeSession($user, $certificationLevel) !== null) {
            return true;
        }

        return ! $this->completedToday($user, $certificationLevel);
    }

    public function completedToday(User $user, string $certificationLevel): bool
    {
        return ExamSession::query()
            ->where('user_id', $user->id)
            ->where('certification_level', $certificationLevel)
            ->where('exam_type', ExamSession::TYPE_MOCK)
            ->where('status', ExamSession::STATUS_COMPLETED)
            ->whereDate('completed_at', today())
            ->exists();
    }

    public function todaysOutcome(User $user, string $certificationLevel): ?string
    {
        return ExamSession::query()
            ->where('user_id', $user->id)
            ->where('certification_level', $certificationLevel)
            ->where('exam_type', ExamSession::TYPE_MOCK)
            ->where('status', ExamSession::STATUS_COMPLETED)
            ->whereDate('completed_at', today())
            ->latest('completed_at')
            ->value('mock_outcome');
    }

    public function activeSession(User $user, string $certificationLevel): ?ExamSession
    {
        return ExamSession::query()
            ->where('user_id', $user->id)
            ->where('certification_level', $certificationLevel)
            ->where('exam_type', ExamSession::TYPE_MOCK)
            ->where('status', ExamSession::STATUS_IN_PROGRESS)
            ->latest()
            ->first();
    }

    public function start(Request $request, User $user, string $certificationLevel): ExamSession
    {
        if ($this->preview->requiresPaywall($request, $certificationLevel)) {
            throw new RuntimeException('Unlock this section to start the daily mock exam.');
        }

        $active = $user->activeExamSession($certificationLevel);

        if ($active !== null) {
            if ($active->isMockExam()) {
                return $active;
            }

            throw new RuntimeException('Finish or continue your current quiz before starting a mock exam.');
        }

        if ($this->completedToday($user, $certificationLevel)) {
            throw new RuntimeException('You have already completed today\'s mock exam. Come back tomorrow.');
        }

        return ExamSession::create([
            'user_id' => $user->id,
            'certification_level' => $certificationLevel,
            'exam_type' => ExamSession::TYPE_MOCK,
            'focus_category' => null,
            'current_difficulty' => 3,
            'ability_estimate' => 0.5,
            'expires_at' => now()->addSeconds(self::TIME_LIMIT_SECONDS),
            'status' => ExamSession::STATUS_IN_PROGRESS,
        ]);
    }

    public function remainingSeconds(ExamSession $session): int
    {
        if ($session->expires_at === null) {
            return 0;
        }

        return max(0, (int) now()->diffInSeconds($session->expires_at, false));
    }

    public function submitAnswer(Request $request, ExamSession $session, Question $question, string $selectedOption): void
    {
        if (! $session->isMockExam() || $session->isComplete()) {
            throw new RuntimeException('Invalid mock exam session.');
        }

        if ($session->isTimedOut()) {
            $this->finalize($session, $this->outcomeFromAbility($session));

            return;
        }

        $difficulty = $question->difficulty;

        DB::transaction(function () use ($request, $session, $question, $selectedOption, $difficulty) {
            $answer = $this->examService->submitAnswer($request, $session, $question, $selectedOption);
            $session->refresh();

            $this->updateAbilityEstimate($session, $answer->is_correct, $difficulty);
            $session->save();
        });

        $session->refresh();

        if ($session->isTimedOut()) {
            $this->finalize($session, $this->outcomeFromAbility($session));

            return;
        }

        if ($session->questions_answered >= self::MAX_QUESTIONS) {
            $this->finalize($session, $this->outcomeFromAbility($session));

            return;
        }

        $outcome = $this->evaluateTermination($session);

        if ($outcome !== null) {
            $this->finalize($session, $outcome);
        }
    }

    public function finalize(ExamSession $session, string $outcome): ExamSession
    {
        $session->update([
            'status' => ExamSession::STATUS_COMPLETED,
            'mock_outcome' => $outcome,
            'completed_at' => now(),
        ]);

        return $session->fresh();
    }

    public function evaluateTermination(ExamSession $session): ?string
    {
        $answered = $session->questions_answered;

        if ($answered < self::MIN_QUESTIONS) {
            return null;
        }

        $ability = (float) ($session->ability_estimate ?? 0.5);

        if ($ability >= self::EARLY_PASS_ABILITY) {
            return ExamSession::MOCK_PASS;
        }

        if ($ability <= self::EARLY_FAIL_ABILITY) {
            return ExamSession::MOCK_FAIL;
        }

        $confidenceMargin = max(0.04, 0.14 - (($answered - self::MIN_QUESTIONS) * 0.002));

        if ($ability >= self::PASS_ABILITY + $confidenceMargin) {
            return ExamSession::MOCK_PASS;
        }

        if ($ability <= self::FAIL_ABILITY - $confidenceMargin) {
            return ExamSession::MOCK_FAIL;
        }

        return null;
    }

    public function outcomeFromAbility(ExamSession $session): string
    {
        if ($session->questions_answered < self::MIN_QUESTIONS) {
            return ExamSession::MOCK_FAIL;
        }

        $ability = (float) ($session->ability_estimate ?? 0.5);

        return $ability >= self::PASS_ABILITY ? ExamSession::MOCK_PASS : ExamSession::MOCK_FAIL;
    }

    private function updateAbilityEstimate(ExamSession $session, bool $isCorrect, int $difficulty): void
    {
        $ability = (float) ($session->ability_estimate ?? 0.5);
        $weight = max(0.6, $difficulty / 5);
        $step = 0.07 * $weight;

        if ($isCorrect) {
            $ability += (1 - $ability) * $step;
        } else {
            $ability -= $ability * $step;
        }

        $session->ability_estimate = round(max(0, min(1, $ability)), 4);
    }
}
