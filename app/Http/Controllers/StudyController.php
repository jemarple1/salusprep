<?php

namespace App\Http\Controllers;

use App\Models\StudySession;
use App\Services\CategoryProficiencyService;
use App\Services\StudyService;
use App\Support\PlatformExercise;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use RuntimeException;

class StudyController extends Controller
{
    public function __construct(
        private StudyService $study,
        private CategoryProficiencyService $proficiency,
    ) {}

    public function index(Request $request): View
    {
        $level = $request->attributes->get('certification_level');
        $user = $request->user();
        $unlocked = $user !== null && $user->hasSectionAccess($level);

        $exercises = PlatformExercise::cardsForLevel($level, $user, $unlocked);

        $data = [
            'exercises' => $exercises,
            'flashcardsUnlocked' => $unlocked,
            'requiresAuth' => $user === null,
            'totalMissed' => $user !== null ? count($this->study->wrongQuestionIds($user, $level)) : null,
            'wrongByCategory' => [],
            'categoryStats' => collect(),
            'activeStudySession' => null,
        ];

        if ($unlocked && $user !== null) {
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

        $this->requireUnlocked($user, $level);

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
            'action' => ['required', 'in:mastered,review'],
        ]);

        $this->study->advance($studySession, $validated['action']);

        return redirect()->route('study.show', [$section, $studySession->fresh()]);
    }

    private function requireUnlocked($user, string $level): void
    {
        abort_unless($user !== null && $user->hasSectionAccess($level), 403, 'Unlock this section to access study tools and proficiency insights.');
    }

    private function authorizeStudySession(Request $request, StudySession $studySession): void
    {
        abort_unless(
            $studySession->certification_level === $request->attributes->get('certification_level'),
            403,
        );

        abort_unless($studySession->user_id === $request->user()?->id, 403);

        $this->requireUnlocked($request->user(), $studySession->certification_level);
    }
}
