<?php

namespace App\Services;

use App\Models\ExamAnswer;
use App\Models\ExamSession;
use App\Models\GuestSectionProgress;
use App\Models\Question;
use App\Models\SectionAccess;
use App\Models\User;
use App\Support\CertificationLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class AdaptiveExamService
{
    public function __construct(private GuestService $guests) {}

    public function sectionAccess(User $user, string $certificationLevel): SectionAccess
    {
        return SectionAccess::firstOrCreate(
            [
                'user_id' => $user->id,
                'certification_level' => $certificationLevel,
            ],
            ['free_questions_used' => 0],
        );
    }

    public function startSession(Request $request, string $certificationLevel): ExamSession
    {
        if (! CertificationLevel::isValid($certificationLevel)) {
            throw new RuntimeException('Invalid certification level.');
        }

        $user = $request->user();

        if ($user !== null) {
            return $this->startUserSession($user, $certificationLevel);
        }

        return $this->startGuestSession($request, $certificationLevel);
    }

    private function startUserSession(User $user, string $certificationLevel): ExamSession
    {
        $access = $this->sectionAccess($user, $certificationLevel);

        if ($access->requiresPayment()) {
            throw new RuntimeException('This section requires payment before starting a new quiz.');
        }

        $existing = $user->activeExamSession($certificationLevel);

        if ($existing !== null) {
            return $existing;
        }

        return ExamSession::create([
            'user_id' => $user->id,
            'certification_level' => $certificationLevel,
            'current_difficulty' => 2,
            'status' => ExamSession::STATUS_IN_PROGRESS,
        ]);
    }

    private function startGuestSession(Request $request, string $certificationLevel): ExamSession
    {
        $guestToken = $this->guests->token($request);
        $progress = $this->guests->progress($guestToken, $certificationLevel);

        if ($progress->requiresPayment()) {
            throw new RuntimeException('Create a free account to continue after your 25 preview questions.');
        }

        $existing = $this->guests->activeExamSession($guestToken, $certificationLevel);

        if ($existing !== null) {
            return $existing;
        }

        return ExamSession::create([
            'guest_token' => $guestToken,
            'certification_level' => $certificationLevel,
            'current_difficulty' => 2,
            'status' => ExamSession::STATUS_IN_PROGRESS,
        ]);
    }

    public function nextQuestion(ExamSession $session): ?Question
    {
        if ($session->isComplete()) {
            return null;
        }

        if ($session->requiresPayment()) {
            return null;
        }

        $answeredIds = $session->answers()->pluck('question_id');

        $question = Question::query()
            ->where('certification_level', $session->certification_level)
            ->where('difficulty', $session->current_difficulty)
            ->whereNotIn('id', $answeredIds)
            ->inRandomOrder()
            ->first();

        if ($question !== null) {
            return $question;
        }

        return Question::query()
            ->where('certification_level', $session->certification_level)
            ->whereNotIn('id', $answeredIds)
            ->orderByRaw('ABS(difficulty - ?)', [$session->current_difficulty])
            ->inRandomOrder()
            ->first();
    }

    public function submitAnswer(ExamSession $session, Question $question, string $selectedOption): ExamAnswer
    {
        if ($session->requiresPayment() || $session->isComplete()) {
            throw new RuntimeException('This exam session cannot accept answers.');
        }

        if ($session->answers()->where('question_id', $question->id)->exists()) {
            throw new RuntimeException('This question has already been answered.');
        }

        $selectedOption = strtoupper($selectedOption);
        $isCorrect = $selectedOption === strtoupper($question->correct_option);

        return DB::transaction(function () use ($session, $question, $selectedOption, $isCorrect) {
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

            if ($session->user_id !== null) {
                $access = $this->sectionAccess($session->user, $session->certification_level);

                if (! $access->isUnlocked()) {
                    $access->increment('free_questions_used');

                    if ($access->fresh()->requiresPayment()) {
                        $session->status = ExamSession::STATUS_PAYWALL;
                    }
                }
            } elseif ($session->guest_token !== null) {
                $progress = GuestSectionProgress::query()
                    ->where('guest_token', $session->guest_token)
                    ->where('certification_level', $session->certification_level)
                    ->lockForUpdate()
                    ->first();

                if ($progress !== null && $progress->free_questions_used < CertificationLevel::FREE_QUESTIONS) {
                    $progress->increment('free_questions_used');

                    if ($progress->fresh()->requiresPayment()) {
                        $session->status = ExamSession::STATUS_PAYWALL;
                    }
                }
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

        $user->examSessions()
            ->where('certification_level', $certificationLevel)
            ->where('status', ExamSession::STATUS_PAYWALL)
            ->update(['status' => ExamSession::STATUS_IN_PROGRESS]);

        return $access->fresh();
    }
}
