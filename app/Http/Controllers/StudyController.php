<?php

namespace App\Http\Controllers;

use App\Models\StudySession;
use App\Services\CategoryProficiencyService;
use App\Services\PreviewAccessService;
use App\Services\StudyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use RuntimeException;

class StudyController extends Controller
{
    public function __construct(
        private StudyService $study,
        private CategoryProficiencyService $proficiency,
        private PreviewAccessService $preview,
    ) {}

    public function index(Request $request): View
    {
        $level = $request->attributes->get('certification_level');
        $user = $request->user();
        $hasAccess = $this->preview->hasAccess($request, $level);
        $unlocked = $user !== null && $user->hasSectionAccess($level);

        $data = [
            'flashcardsAvailable' => $hasAccess,
            'requiresAuth' => $user === null,
            'totalMissed' => $user !== null ? count($this->study->wrongQuestionIds($user, $level)) : null,
            'wrongByCategory' => [],
            'categoryStats' => collect(),
            'activeStudySession' => null,
            'activeExamSession' => $user?->activeExamSession($level),
        ];

        if ($hasAccess && $user !== null) {
            $data['wrongByCategory'] = $this->study->wrongCountsByCategory($user, $level);
            $data['categoryStats'] = $this->proficiency->forUser($user, $level);
            $data['activeStudySession'] = $this->study->activeSession($user, $level);
            $data['totalMissed'] = count($this->study->wrongQuestionIds($user, $level));
        }

        return view('study.index', $data);
    }

    public function start(Request $request): RedirectResponse
    {
        $level = $request->attributes->get('certification_level');
        $slug = $request->attributes->get('section_slug');
        $user = $request->user();

        $this->requirePlatformAccess($request, $user, $level);

        $validated = $request->validate([
            'category' => ['nullable', 'string', 'max:100'],
        ]);

        $category = $validated['category'] ?? null;

        try {
            $session = $this->study->startSession($user, $level, $category);
        } catch (RuntimeException $exception) {
            return redirect()
                ->route('study.index', $slug)
                ->withErrors(['study' => $exception->getMessage()]);
        }

        return redirect()->route('study.show', [$slug, $session]);
    }

    public function show(Request $request, string $section, StudySession $studySession): View|RedirectResponse
    {
        $this->authorizeStudySession($request, $studySession);

        if ($studySession->isComplete()) {
            return view('study.complete', ['studySession' => $studySession]);
        }

        $question = $studySession->currentQuestion();

        if ($question === null) {
            $studySession->update([
                'status' => StudySession::STATUS_COMPLETED,
                'completed_at' => now(),
            ]);

            return redirect()->route('study.show', [$section, $studySession]);
        }

        $user = $request->user();
        $lastWrong = $this->study->lastWrongAnswer(
            $user,
            $studySession->certification_level,
            $question,
        );

        return view('study.show', [
            'studySession' => $studySession,
            'question' => $question,
            'lastWrong' => $lastWrong,
            'cardNumber' => $studySession->cards_studied + 1,
        ]);
    }

    public function advance(Request $request, string $section, StudySession $studySession): RedirectResponse
    {
        $this->authorizeStudySession($request, $studySession);

        if ($studySession->isComplete()) {
            return redirect()->route('study.show', [$section, $studySession]);
        }

        $validated = $request->validate([
            'action' => ['required', 'in:weak,strong,review,mastered'],
        ]);

        $this->study->advance($studySession, $validated['action'], $request->user());

        $this->preview->recordAction($request, $studySession->certification_level);

        if ($this->preview->requiresPaywall($request, $studySession->certification_level)) {
            return redirect()->route('platform.paywall', $section);
        }

        return redirect()->route('study.show', [$section, $studySession->fresh()]);
    }

    private function requirePlatformAccess(Request $request, $user, string $level): void
    {
        abort_unless($user !== null, 403, 'Sign in to use flashcards.');
        abort_unless($this->preview->hasAccess($request, $level), 403);
    }

    private function authorizeStudySession(Request $request, StudySession $studySession): void
    {
        abort_unless(
            $studySession->certification_level === $request->attributes->get('certification_level'),
            403,
        );

        abort_unless($studySession->user_id === $request->user()?->id, 403);

        $this->requirePlatformAccess($request, $request->user(), $studySession->certification_level);
    }
}
