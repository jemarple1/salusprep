<?php

namespace App\Http\Controllers;

use App\Models\ExamSession;
use App\Models\Question;
use App\Services\AdaptiveExamService;
use App\Services\MockExamService;
use App\Services\PreviewAccessService;
use App\Support\WelcomeReturn;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use RuntimeException;

class MockExamController extends Controller
{
    public function __construct(
        private MockExamService $mockExam,
        private AdaptiveExamService $examService,
        private PreviewAccessService $preview,
    ) {}

    public function start(Request $request): RedirectResponse
    {
        $slug = $request->attributes->get('section_slug');
        $level = $request->attributes->get('certification_level');

        try {
            $session = $this->mockExam->start($request, $level);
        } catch (RuntimeException $exception) {
            $redirectRoute = $request->user() !== null
                ? route('platform.dashboard', $slug)
                : route('platform.home', $slug);

            return redirect()
                ->to($redirectRoute)
                ->withErrors(['mock_exam' => $exception->getMessage()]);
        }

        if ($request->input(WelcomeReturn::QUERY_PARAM) === WelcomeReturn::QUERY_VALUE) {
            WelcomeReturn::mark($request, $slug);
        }

        return redirect()->route('mock-exam.show', [$slug, $session]);
    }

    public function show(Request $request, string $section, ExamSession $session): View|RedirectResponse
    {
        $this->authorizeMockSession($request, $session);

        if ($session->isComplete()) {
            return redirect()->route('mock-exam.outcome', [$section, $session]);
        }

        if ($session->isTimedOut()) {
            $this->mockExam->finalize($session, $this->mockExam->outcomeFromAbility($session));

            return redirect()->route('mock-exam.outcome', [$section, $session]);
        }

        $question = $this->examService->nextQuestion($session);

        if ($question === null) {
            $this->mockExam->finalize($session, $this->mockExam->outcomeFromAbility($session));

            return redirect()->route('mock-exam.outcome', [$section, $session]);
        }

        return view('mock-exam.show', [
            'session' => $session,
            'question' => $question,
            'questionNumber' => $session->questions_answered + 1,
            'remainingSeconds' => $this->mockExam->remainingSeconds($session),
        ]);
    }

    public function answer(Request $request, string $section, ExamSession $session, Question $question): RedirectResponse
    {
        $this->authorizeMockSession($request, $session);

        $validated = $request->validate([
            'selected_option' => ['required', 'in:A,B,C,D'],
        ]);

        abort_unless($question->certification_level === $session->certification_level, 403);

        $this->mockExam->submitAnswer($request, $session, $question, $validated['selected_option']);

        $session->refresh();

        if ($this->preview->requiresPaywall($request, $session->certification_level)) {
            return redirect()->route('platform.paywall', $section);
        }

        if ($session->isComplete()) {
            return redirect()->route('mock-exam.outcome', [$section, $session]);
        }

        return redirect()->route('mock-exam.show', [$section, $session]);
    }

    public function outcome(Request $request, string $section, ExamSession $session): View|RedirectResponse
    {
        $this->authorizeMockSession($request, $session);

        if (! $session->isComplete()) {
            return redirect()->route('mock-exam.show', [$section, $session]);
        }

        return view('mock-exam.outcome', [
            'session' => $session,
        ]);
    }

    private function authorizeMockSession(Request $request, ExamSession $session): void
    {
        abort_unless($session->isMockExam(), 404);
        abort_unless(
            $session->certification_level === $request->attributes->get('certification_level'),
            403,
        );
        abort_unless($this->mockExam->sessionOwnedBy($request, $session), 403);
        abort_unless($this->preview->hasAccess($request, $session->certification_level), 403);
    }
}
