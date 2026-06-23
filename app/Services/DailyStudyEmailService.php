<?php

namespace App\Services;

use App\Mail\DailyStudyPlanMail;
use App\Models\ExamSession;
use App\Models\ExerciseScenarioCompletion;
use App\Models\Question;
use App\Models\SectionAccess;
use App\Models\User;
use App\Support\CertificationLevel;
use App\Support\PlatformExercise;
use App\Support\WelcomeReturn;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class DailyStudyEmailService
{
    public function __construct(
        private WelcomeDailyPlanService $dailyPlan,
        private StudyService $study,
    ) {}

    public function shouldSend(SectionAccess $access, User $user, ?Carbon $onDate = null): bool
    {
        if (! $access->isUnlocked()) {
            return false;
        }

        if (! $user->daily_study_email_opt_in) {
            return false;
        }

        $date = ($onDate ?? now())->toDateString();

        if ($access->last_daily_study_email_sent_on?->toDateString() === $date) {
            return false;
        }

        if ($access->exam_date !== null) {
            $examDate = $access->exam_date->toDateString();

            if ($examDate <= $date) {
                return false;
            }
        }

        return true;
    }

    /** @return array<string, mixed> */
    public function buildPayload(User $user, SectionAccess $access, ?Carbon $onDate = null): array
    {
        $level = $access->certification_level;
        $slug = CertificationLevel::slug($level);
        $dayNumber = $this->dailyPlan->daysSinceUnlock($access, $user) + 1;
        $planDate = ($onDate ?? now())->timezone(config('daily_study_email.timezone'));

        $items = $this->dailyPlan->checklistItemsForEmail($user, $level, $access);
        $featuredSkill = $this->featuredUncoveredSkill($user, $level, $dayNumber);
        $reviewFact = $this->reviewFactForUser($user, $level, $dayNumber);
        $subjectLine = $this->subjectLine($dayNumber, $planDate);

        return [
            'user' => $user,
            'access' => $access,
            'sectionLabel' => CertificationLevel::label($level),
            'sectionSlug' => $slug,
            'dayNumber' => $dayNumber,
            'planDateLabel' => $planDate->format('l, F j'),
            'items' => $items,
            'featuredSkill' => $featuredSkill,
            'reviewFact' => $reviewFact,
            'welcomeUrl' => WelcomeReturn::url(route('platform.welcome', $slug)),
            'unsubscribeUrl' => URL::signedRoute('email.daily-study.unsubscribe', ['user' => $user->id]),
            'subject' => $subjectLine['subject'],
            'preview' => $subjectLine['preview'],
            'examCountdownDays' => $this->examCountdownDays($access, $onDate),
        ];
    }

    public function send(SectionAccess $access, User $user, ?Carbon $onDate = null): bool
    {
        if (! $this->shouldSend($access, $user, $onDate)) {
            return false;
        }

        $payload = $this->buildPayload($user, $access, $onDate);

        Mail::to($user->email)->send(new DailyStudyPlanMail($payload));

        $access->last_daily_study_email_sent_on = ($onDate ?? now())->toDateString();
        $access->save();

        return true;
    }

    public function sendTest(SectionAccess $access, User $user, ?Carbon $onDate = null): bool
    {
        if (! $access->isUnlocked()) {
            return false;
        }

        $payload = $this->buildPayload($user, $access, $onDate);
        $payload['subject'] = '[Test] '.$payload['subject'];

        Mail::to($user->email)->send(new DailyStudyPlanMail($payload));

        return true;
    }

    /** @return array{subject: string, preview: string} */
    public function subjectLine(int $dayNumber, Carbon $planDate): array
    {
        $subjects = config('daily_study_email.subjects', []);

        if ($subjects === []) {
            return [
                'subject' => 'Your daily study checklist is ready',
                'preview' => 'Open SalusPrep to see today\'s plan.',
            ];
        }

        $template = $subjects[($dayNumber - 1) % count($subjects)];

        return [
            'subject' => str_replace('{date}', $planDate->format('M j'), $template['subject']),
            'preview' => str_replace('{date}', $planDate->format('M j'), $template['preview']),
        ];
    }

    /** @return array<string, mixed>|null */
    public function featuredUncoveredSkill(User $user, string $level, int $dayNumber): ?array
    {
        $cards = PlatformExercise::cardsForLevel($level);

        if ($cards === []) {
            return null;
        }

        $touchedSlugs = ExerciseScenarioCompletion::query()
            ->where('user_id', $user->id)
            ->where('certification_level', $level)
            ->distinct()
            ->pluck('exercise_slug');

        $uncovered = collect($cards)
            ->filter(fn (array $card) => ! $touchedSlugs->contains($card['slug']))
            ->values();

        if ($uncovered->isEmpty()) {
            $completionCounts = ExerciseScenarioCompletion::query()
                ->where('user_id', $user->id)
                ->where('certification_level', $level)
                ->selectRaw('exercise_slug, COUNT(*) as completions')
                ->groupBy('exercise_slug')
                ->pluck('completions', 'exercise_slug');

            $uncovered = collect($cards)
                ->sortBy(fn (array $card) => $completionCounts[$card['slug']] ?? 0)
                ->values();
        }

        $index = ($dayNumber - 1) % $uncovered->count();
        $skill = $uncovered[$index];

        return [
            'title' => $skill['title'],
            'description' => $skill['description'] ?? 'Interactive scenario practice.',
            'url' => WelcomeReturn::url($skill['url']),
            'uncovered' => ! $touchedSlugs->contains($skill['slug']),
        ];
    }

    /** @return array<string, mixed> */
    public function reviewFactForUser(User $user, string $level, int $dayNumber): array
    {
        $wrongIds = $this->study->wrongQuestionIds($user, $level);

        if ($wrongIds === []) {
            return [
                'title' => 'Study tip',
                'body' => config('daily_study_email.fallback_fact'),
                'category' => null,
                'hasMiss' => false,
            ];
        }

        $questionId = $wrongIds[($dayNumber - 1) % count($wrongIds)];
        $question = Question::query()->find($questionId);

        if ($question === null) {
            return [
                'title' => 'Study tip',
                'body' => config('daily_study_email.fallback_fact'),
                'category' => null,
                'hasMiss' => false,
            ];
        }

        $lastWrong = $this->study->lastWrongAnswer($user, $level, $question);
        $explanation = trim((string) $question->explanation);

        return [
            'title' => 'From a question you missed',
            'category' => $question->category,
            'stem' => Str::limit($question->stem, 140),
            'yourAnswer' => $lastWrong?->selected_option,
            'correctAnswer' => $question->correct_option,
            'body' => Str::limit($explanation !== '' ? $explanation : 'Review this topic in flashcards and try a focus quiz today.', 320),
            'hasMiss' => true,
        ];
    }

    public function examCountdownDays(SectionAccess $access, ?Carbon $onDate = null): ?int
    {
        if ($access->exam_date === null) {
            return null;
        }

        $today = ($onDate ?? now())->startOfDay();
        $examDate = $access->exam_date->copy()->startOfDay();

        return (int) $today->diffInDays($examDate, false);
    }

    public function mockCompletedTodayForUser(User $user, string $level, ?Carbon $onDate = null): bool
    {
        $date = ($onDate ?? now())->toDateString();

        return ExamSession::query()
            ->where('user_id', $user->id)
            ->where('certification_level', $level)
            ->where('exam_type', ExamSession::TYPE_MOCK)
            ->where('status', ExamSession::STATUS_COMPLETED)
            ->whereDate('completed_at', $date)
            ->exists();
    }

    public function activeMockForUser(User $user, string $level): ?ExamSession
    {
        return ExamSession::query()
            ->where('user_id', $user->id)
            ->where('certification_level', $level)
            ->where('exam_type', ExamSession::TYPE_MOCK)
            ->where('status', ExamSession::STATUS_IN_PROGRESS)
            ->latest()
            ->first();
    }
}
