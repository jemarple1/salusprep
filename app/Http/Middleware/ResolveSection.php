<?php

namespace App\Http\Middleware;

use App\Models\SectionAccess;
use App\Services\ExamCountdownService;
use App\Services\GuestService;
use App\Services\PreviewAccessService;
use App\Support\CertificationLevel;
use App\Support\PageSeo;
use App\Support\WelcomeReturn;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolveSection
{
    public function __construct(
        private ExamCountdownService $countdown,
        private PreviewAccessService $preview,
        private GuestService $guests,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $slug = $request->route('section');

        if (! is_string($slug) || ! CertificationLevel::isValidSlug($slug)) {
            abort(404);
        }

        $level = CertificationLevel::fromSlug($slug);

        $request->attributes->set('certification_level', $level);
        $request->attributes->set('section_slug', $slug);

        $request->session()->put(\App\Http\Controllers\AuthController::REGISTER_SECTION_SESSION_KEY, $slug);

        if (! $this->preview->isUnlocked($request, $level)) {
            $this->guests->ensureSectionPreview($request, $level);
        }

        $examCountdown = null;
        $user = $request->user();

        if ($user !== null) {
            $examDate = SectionAccess::query()
                ->where('user_id', $user->id)
                ->where('certification_level', $level)
                ->value('exam_date');

            $examCountdown = $this->countdown->forDate($examDate);
        }

        $welcomeNavLink = null;

        if ($user !== null && $user->hasSectionAccess($level) && $examCountdown === null) {
            $welcomeNavLink = [
                'label' => 'Open your daily study checklist',
                'short_label' => 'Today',
            ];
        }

        $isUnlocked = $user !== null && $user->hasSectionAccess($level);
        $previewExpired = ! $isUnlocked && $this->preview->requiresPaywall($request, $level);
        $totalSeconds = $this->preview->totalSeconds();
        $remainingSeconds = $isUnlocked ? $totalSeconds : $this->preview->remainingSeconds($request, $level);
        $expiresAt = $isUnlocked ? null : $this->preview->expiresAt($request, $level);

        if ($request->query(WelcomeReturn::QUERY_PARAM) === WelcomeReturn::QUERY_VALUE) {
            WelcomeReturn::mark($request, $slug);
        }

        $showWelcomeReturn = $user !== null
            && $user->hasSectionAccess($level)
            && WelcomeReturn::active($request, $slug);

        view()->share([
            'sectionLevel' => $level,
            'sectionSlug' => $slug,
            'sectionLabel' => CertificationLevel::label($level),
            'sectionDescription' => CertificationLevel::descriptions()[$level],
            'sectionHeaderTag' => CertificationLevel::headerTag($level),
            'sectionPracticeHeadline' => CertificationLevel::practiceHeadline($level),
            'platformSeo' => PageSeo::forPlatform($level),
            'platformSections' => collect(CertificationLevel::all())->map(fn (string $l) => [
                'level' => $l,
                'slug' => CertificationLevel::slug($l),
                'label' => CertificationLevel::label($l),
                'active' => $l === $level,
            ]),
            'platformSwitcherGroups' => collect(CertificationLevel::platformSwitcherGroups())
                ->map(fn (array $group) => [
                    'title' => $group['title'],
                    'items' => collect($group['levels'])->map(fn (string $l) => [
                        'level' => $l,
                        'slug' => CertificationLevel::slug($l),
                        'label' => CertificationLevel::label($l),
                        'active' => $l === $level,
                    ])->all(),
                ])
                ->all(),
            'examCountdown' => $examCountdown,
            'welcomeNavLink' => $welcomeNavLink,
            'showWelcomeReturn' => $showWelcomeReturn,
            'previewExpired' => $previewExpired,
            'previewTimer' => [
                'href' => $isUnlocked
                    ? route('platform.welcome', $slug)
                    : route('platform.paywall', $slug),
                'isUnlocked' => $isUnlocked,
                'remainingSeconds' => $remainingSeconds,
                'totalSeconds' => $totalSeconds,
                'remainingMinutes' => $isUnlocked ? null : $this->preview->remainingMinutes($request, $level),
                'expiresAt' => $expiresAt?->toIso8601String(),
                'symbol' => $isUnlocked ? '✓' : '◷',
                'ariaLabel' => $isUnlocked
                    ? 'Full Access — open your welcome page'
                    : ($previewExpired
                        ? 'Preview ended — view unlock options'
                        : 'Preview time remaining'),
            ],
        ]);

        return $next($request);
    }
}
