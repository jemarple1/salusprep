<?php

namespace App\Http\Controllers;

use App\Models\ExamSession;
use App\Models\Question;
use App\Services\AdaptiveExamService;
use App\Services\FocusCategoryService;
use App\Services\GuestService;
use App\Services\PreviewAccessService;
use App\Services\StudyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExamController extends Controller
{
    public function __construct(
        private AdaptiveExamService $examService,
        private GuestService $guests,
        private StudyService $study,
        private PreviewAccessService $preview,
        private FocusCategoryService $focusCategory,
    ) {}

    public function start(Request $request): RedirectResponse
    {
        $slug = $request->attributes->get('section_slug');
        $level = $request->attributes->get('certification_level');

        $validated = $request->validate([
            'focus_category' => ['nullable', 'string', 'max:100'],
        ]);

        $focusCategory = $validated['focus_category'] ?? null;

        if ($focusCategory !== null && $this->focusCategory->isValidCategory($level, $focusCategory)) {
            $this->focusCategory->pin($request, $level, $focusCategory);
        }

        try {
            $session = $this->examService->startSession(
                $request,
                $level,
                $focusCategory,
            );
        } catch (\RuntimeException $exception) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['exam' => $exception->getMessage()]);
        }

        return redirect()->route('exam.show', [$slug, $session]);
    }

    public function show(Request $request, string $section, ExamSession $session): View|RedirectResponse
    {
        $this->authorizeSession($request, $session);

        if ($session->isComplete()) {
            return redirect()->route('exam.results', [$section, $session]);
        }

        if ($session->hasReachedQuestionLimit()) {
            $this->examService->completeSession($session);

            return redirect()->route('exam.results', [$section, $session]);
        }

        $lastAnswer = $session->answers()->with('question')->latest('id')->first();
        $reviewedAnswerId = session('exam.reviewed_answer_id.'.$session->id);
        $reviewMode = $lastAnswer !== null && $lastAnswer->id !== $reviewedAnswerId;

        if ($reviewMode) {
            $question = $lastAnswer->question;
            $questionNumber = $session->questions_answered;
        } else {
            $question = $this->examService->nextQuestion($session);

            if ($question === null) {
                $this->examService->completeSession($session);

                return redirect()
                    ->route('exam.results', [$section, $session])
                    ->with('success', 'Quiz complete.');
            }

            $questionNumber = $session->questions_answered + 1;
            $lastAnswer = null;
        }

        $user = $request->user();
        $slug = $request->attributes->get('section_slug');
        $hasAccess = $this->preview->hasAccess($request, $session->certification_level);
        $canStudy = $user !== null && $hasAccess;
        $activeStudySession = $canStudy ? $this->study->activeSession($user, $session->certification_level) : null;
        $studyDeckUrl = $canStudy && $activeStudySession
            ? route('study.show', [$slug, $activeStudySession])
            : route('study.index', $slug);

        return view('exam.show', [
            'session' => $session,
            'question' => $question,
            'lastAnswer' => $lastAnswer,
            'reviewMode' => $reviewMode,
            'questionNumber' => $questionNumber,
            'totalQuestions' => $session->targetQuestionCount(),
            'studyDeckUrl' => $studyDeckUrl,
        ]);
    }

    public function continue(Request $request, string $section, ExamSession $session): RedirectResponse
    {
        $this->authorizeSession($request, $session);

        $lastAnswer = $session->answers()->latest('id')->first();

        if ($lastAnswer !== null) {
            session(['exam.reviewed_answer_id.'.$session->id => $lastAnswer->id]);
        }

        return redirect()->route('exam.show', [$section, $session]);
    }

    public function answer(Request $request, string $section, ExamSession $session, Question $question): RedirectResponse
    {
        $this->authorizeSession($request, $session);

        $validated = $request->validate([
            'selected_option' => ['required', 'in:A,B,C,D'],
        ]);

        $this->examService->submitAnswer($request, $session, $question, $validated['selected_option']);

        $session->refresh();

        if ($this->preview->requiresPaywall($request, $session->certification_level)) {
            return redirect()->route('platform.paywall', $section);
        }

        if ($session->isComplete()) {
            return redirect()->route('exam.results', [$section, $session]);
        }

        return redirect()->route('exam.show', [$section, $session]);
    }

    public function paywall(Request $request, string $section, ExamSession $session): RedirectResponse
    {
        $this->authorizeSession($request, $session);

        return redirect()->route('platform.paywall', $section);
    }

    public function results(Request $request, string $section, ExamSession $session): View
    {
        $this->authorizeSession($request, $session);

        $session->load(['answers.question']);

        $platformCorrectPercents = Question::platformCorrectPercentsFor(
            $session->answers->pluck('question_id')->unique()->all(),
        );

        return view('exam.results', [
            'session' => $session,
            'platformCorrectPercents' => $platformCorrectPercents,
            'activeExamSession' => $this->activeExamSessionFor($request),
            'hasAccess' => $this->preview->hasAccess($request, $session->certification_level),
        ]);
    }

    private function activeExamSessionFor(Request $request): ?ExamSession
    {
        $level = $request->attributes->get('certification_level');
        $user = $request->user();

        if ($user !== null) {
            return $user->activeExamSession($level);
        }

        return $this->guests->activeExamSession($this->guests->token($request), $level);
    }

    public function finish(Request $request, string $section, ExamSession $session): RedirectResponse
    {
        $this->authorizeSession($request, $session);

        $this->examService->completeSession($session);

        return redirect()->route('exam.results', [$section, $session]);
    }

    private function authorizeSession(Request $request, ExamSession $session): void
    {
        abort_unless(
            $session->certification_level === $request->attributes->get('certification_level'),
            403,
        );

        $user = $request->user();

        if ($user !== null) {
            abort_unless($session->user_id === $user->id, 403);

            return;
        }

        abort_unless(
            $session->guest_token === $this->guests->token($request),
            403,
        );
    }
}
