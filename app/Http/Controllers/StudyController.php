<?php

namespace App\Http\Controllers;

use App\Models\StudySession;
use App\Services\CategoryProficiencyService;
use App\Services\GuestService;
use App\Services\PreviewAccessService;
use App\Services\PublicFlashcardDeckService;
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
        private GuestService $guests,
        private PublicFlashcardDeckService $publicDecks,
    ) {}

    public function index(Request $request): View
    {
        $level = $request->attributes->get('certification_level');
        $user = $request->user();
        $guestToken = $user === null ? $this->guests->token($request) : null;
        $hasAccess = $this->preview->hasAccess($request, $level);
        $unlocked = $user !== null && $user->hasSectionAccess($level);

        $data = [
            'flashcardsAvailable' => $hasAccess,
            'totalMissed' => 0,
            'wrongByCategory' => [],
            'categoryStats' => collect(),
            'activeStudySession' => null,
            'activeStudySessions' => collect(),
            'activePublicSessions' => collect(),
            'activePersonalSessions' => collect(),
            'activeAllDeckSession' => null,
            'activeExamSession' => $user !== null
                ? $user->activeExamSession($level)
                : ($guestToken !== null ? $this->guests->activeExamSession($guestToken, $level) : null),
            'publicDecks' => collect(),
        ];

        if ($hasAccess) {
            if ($user !== null) {
                $data['wrongByCategory'] = $this->study->wrongCountsByCategory($user, $level);
                $data['categoryStats'] = $this->proficiency->forUser($user, $level);
                $activeSessions = $this->study->activeSessions($user, $level);
                $data['activeStudySessions'] = $activeSessions;
                $data['activeStudySession'] = $activeSessions->first();
                $data['activePublicSessions'] = $activeSessions
                    ->filter(fn (StudySession $session) => $session->public_deck_key !== null)
                    ->keyBy('public_deck_key');
                $data['activePersonalSessions'] = $activeSessions
                    ->filter(fn (StudySession $session) => $session->public_deck_key === null && $session->filter_category !== null)
                    ->keyBy('filter_category');
                $data['activeAllDeckSession'] = $this->study->activePersonalSession($user, $level);
                $data['totalMissed'] = count($this->study->wrongQuestionIds($user, $level));
                $data['publicDecks'] = $this->publicDecks
                    ->recommendedDecks($request, $level, $data['categoryStats'])
                    ->map(function ($deck) use ($data) {
                        $deck->active_session = $data['activePublicSessions']->get($deck->key);

                        return $deck;
                    });
            } elseif ($guestToken !== null) {
                $data['wrongByCategory'] = $this->study->wrongCountsByCategoryForGuest($guestToken, $level);
                $data['totalMissed'] = count($this->study->wrongQuestionIdsForGuest($guestToken, $level));
                $activeSessions = $this->study->activeSessionsForGuest($guestToken, $level);
                $data['activeStudySessions'] = $activeSessions;
                $data['activeStudySession'] = $activeSessions->first();
                $data['activePublicSessions'] = $activeSessions
                    ->filter(fn (StudySession $session) => $session->public_deck_key !== null)
                    ->keyBy('public_deck_key');
                $data['activePersonalSessions'] = $activeSessions
                    ->filter(fn (StudySession $session) => $session->public_deck_key === null && $session->filter_category !== null)
                    ->keyBy('filter_category');
                $data['activeAllDeckSession'] = $this->study->activePersonalSessionForGuest($guestToken, $level);
                $data['categoryStats'] = $this->proficiency->forGuest($guestToken, $level);
                $data['publicDecks'] = $this->publicDecks
                    ->recommendedDecks($request, $level, $data['categoryStats'])
                    ->map(function ($deck) use ($data) {
                        $deck->active_session = $data['activePublicSessions']->get($deck->key);

                        return $deck;
                    });
            }
        }

        return view('study.index', $data);
    }

    public function deck(Request $request): View|RedirectResponse
    {
        $level = $request->attributes->get('certification_level');
        $slug = $request->attributes->get('section_slug');
        $user = $request->user();
        $guestToken = $user === null ? $this->guests->token($request) : null;

        $this->requirePlatformAccess($request, $level);

        $activeSession = $user !== null
            ? $this->study->activeAllDeckSession($user, $level)
            : ($guestToken !== null ? $this->study->activeAllDeckSessionForGuest($guestToken, $level) : null);

        if ($activeSession !== null) {
            return redirect()->route('study.show', [$slug, $activeSession]);
        }

        $totalMissed = $user !== null
            ? count($this->study->wrongQuestionIds($user, $level))
            : ($guestToken !== null ? count($this->study->wrongQuestionIdsForGuest($guestToken, $level)) : 0);

        if ($totalMissed === 0) {
            return redirect()
                ->route('study.index', $slug)
                ->withErrors(['study' => 'No missed questions to review yet. Complete a quiz first.']);
        }

        try {
            $session = $user !== null
                ? $this->study->startSession($user, $level)
                : $this->study->startSessionForGuest($guestToken, $level, null, $this->guests->activityDeviceId($request));
        } catch (RuntimeException $exception) {
            return redirect()
                ->route('study.index', $slug)
                ->withErrors(['study' => $exception->getMessage()]);
        }

        return redirect()->route('study.show', [$slug, $session]);
    }

    public function start(Request $request): RedirectResponse
    {
        $level = $request->attributes->get('certification_level');
        $slug = $request->attributes->get('section_slug');
        $user = $request->user();
        $guestToken = $user === null ? $this->guests->token($request) : null;

        $this->requirePlatformAccess($request, $level);

        $validated = $request->validate([
            'category' => ['nullable', 'string', 'max:100'],
        ]);

        $category = $validated['category'] ?? null;

        $existing = $user !== null
            ? $this->study->activePersonalSession($user, $level, $category)
            : $this->study->activePersonalSessionForGuest($guestToken, $level, $category);

        if ($existing !== null) {
            return redirect()->route('study.show', [$slug, $existing]);
        }

        try {
            $session = $user !== null
                ? $this->study->startSession($user, $level, $category)
                : $this->study->startSessionForGuest($guestToken, $level, $category, $this->guests->activityDeviceId($request));
        } catch (RuntimeException $exception) {
            return redirect()
                ->route('study.index', $slug)
                ->withErrors(['study' => $exception->getMessage()]);
        }

        return redirect()->route('study.show', [$slug, $session]);
    }

    public function startPublic(Request $request): RedirectResponse
    {
        $level = $request->attributes->get('certification_level');
        $slug = $request->attributes->get('section_slug');
        $user = $request->user();
        $guestToken = $user === null ? $this->guests->token($request) : null;

        $this->requirePlatformAccess($request, $level);

        $validated = $request->validate([
            'deck_key' => ['required', 'string', 'max:100'],
        ]);

        abort_unless(
            $this->publicDecks->isValidDeckKey($level, $validated['deck_key']),
            404,
        );

        $existing = $user !== null
            ? $this->study->activePublicSession($user, $level, $validated['deck_key'])
            : $this->study->activePublicSessionForGuest($guestToken, $level, $validated['deck_key']);

        if ($existing !== null) {
            return redirect()->route('study.show', [$slug, $existing]);
        }

        try {
            $session = $user !== null
                ? $this->study->startPublicSession($user, $level, $validated['deck_key'])
                : $this->study->startPublicSessionForGuest(
                    $guestToken,
                    $level,
                    $validated['deck_key'],
                    $this->guests->activityDeviceId($request),
                );
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
        $guestToken = $user === null ? $this->guests->token($request) : null;

        $lastWrong = $user !== null
            ? $this->study->lastWrongAnswer($user, $studySession->certification_level, $question)
            : $this->study->lastWrongAnswerForGuest($guestToken, $studySession->certification_level, $question);

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

        $user = $request->user();

        if ($user !== null) {
            $this->study->advance($studySession, $validated['action'], $user);
        } else {
            $this->study->advanceGuest($studySession, $validated['action']);
        }

        $this->preview->recordAction($request, $studySession->certification_level);

        if ($this->preview->requiresPaywall($request, $studySession->certification_level)) {
            return redirect()->route('platform.paywall', $section);
        }

        return redirect()->route('study.show', [$section, $studySession->fresh()]);
    }

    private function requirePlatformAccess(Request $request, string $level): void
    {
        abort_unless($this->preview->hasAccess($request, $level), 403);
    }

    private function authorizeStudySession(Request $request, StudySession $studySession): void
    {
        abort_unless(
            $studySession->certification_level === $request->attributes->get('certification_level'),
            403,
        );

        $user = $request->user();

        if ($user !== null) {
            abort_unless($studySession->user_id === $user->id, 403);
        } else {
            $guestToken = $this->guests->token($request);
            abort_unless(
                $studySession->user_id === null && $studySession->guest_token === $guestToken,
                403,
            );
        }

        $this->requirePlatformAccess($request, $studySession->certification_level);
    }
}
