<?php

namespace App\Http\Controllers;

use App\Models\ExamSession;
use App\Models\Question;
use App\Services\AdaptiveExamService;
use App\Services\GuestService;
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
    ) {}

    public function start(Request $request): RedirectResponse
    {
        $slug = $request->attributes->get('section_slug');

        try {
            $session = $this->examService->startSession($request, $request->attributes->get('certification_level'));
        } catch (\RuntimeException $exception) {
            return redirect()
                ->route('platform.home', $slug)
                ->withErrors(['exam' => $exception->getMessage()]);
        }

        if ($session->requiresPayment()) {
            return redirect()->route('exam.paywall', [$slug, $session]);
        }

        return redirect()->route('exam.show', [$slug, $session]);
    }

    public function show(Request $request, string $section, ExamSession $session): View|RedirectResponse
    {
        $this->authorizeSession($request, $session);

        if ($session->requiresPayment()) {
            return redirect()->route('exam.paywall', [$section, $session]);
        }

        if ($session->isComplete()) {
            return redirect()->route('exam.results', [$section, $session]);
        }

        if ($session->hasReachedQuestionLimit()) {
            $this->examService->completeSession($session);

            return redirect()->route('exam.results', [$section, $session]);
        }

        $question = $this->examService->nextQuestion($session);

        if ($question === null) {
            $this->examService->completeSession($session);

            return redirect()
                ->route('exam.results', [$section, $session])
                ->with('success', 'Quiz complete.');
        }

        $lastAnswer = $session->answers()->with('question')->latest('id')->first();

        $user = $request->user();
        $slug = $request->attributes->get('section_slug');
        $canStudy = $user !== null && $user->hasSectionAccess($session->certification_level);
        $activeStudySession = $canStudy ? $this->study->activeSession($user, $session->certification_level) : null;
        $studyDeckUrl = $canStudy && $activeStudySession
            ? route('study.show', [$slug, $activeStudySession])
            : route('study.index', $slug);

        return view('exam.show', [
            'session' => $session,
            'question' => $question,
            'lastAnswer' => $lastAnswer,
            'questionNumber' => $session->questions_answered + 1,
            'totalQuestions' => $session->targetQuestionCount(),
            'studyDeckUrl' => $studyDeckUrl,
        ]);
    }

    public function answer(Request $request, string $section, ExamSession $session, Question $question): RedirectResponse
    {
        $this->authorizeSession($request, $session);

        $validated = $request->validate([
            'selected_option' => ['required', 'in:A,B,C,D'],
        ]);

        $this->examService->submitAnswer($session, $question, $validated['selected_option']);

        $session->refresh();

        if ($session->requiresPayment()) {
            return redirect()->route('exam.paywall', [$section, $session]);
        }

        if ($session->isComplete()) {
            return redirect()->route('exam.results', [$section, $session]);
        }

        return redirect()->route('exam.show', [$section, $session]);
    }

    public function paywall(Request $request, string $section, ExamSession $session): View|RedirectResponse
    {
        $this->authorizeSession($request, $session);

        if ($session->sectionIsUnlocked()) {
            $session->update(['status' => ExamSession::STATUS_IN_PROGRESS]);

            return redirect()->route('exam.show', [$section, $session]);
        }

        if (! $session->requiresPayment()) {
            return redirect()->route('exam.show', [$section, $session]);
        }

        if ($request->user() === null) {
            $request->session()->put('url.intended', route('exam.paywall', [$section, $session]));
        }

        return view('exam.paywall', [
            'session' => $session,
            'requiresAuth' => $request->user() === null,
        ]);
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
        ]);
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
