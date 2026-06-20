<?php

namespace App\Http\Middleware;

use App\Models\SectionAccess;
use App\Services\ExamCountdownService;
use App\Services\PreviewAccessService;
use App\Support\CertificationLevel;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolveSection
{
    public function __construct(
        private ExamCountdownService $countdown,
        private PreviewAccessService $preview,
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

        $examCountdown = null;
        $user = $request->user();

        if ($user !== null) {
            $examDate = SectionAccess::query()
                ->where('user_id', $user->id)
                ->where('certification_level', $level)
                ->value('exam_date');

            $examCountdown = $this->countdown->forDate($examDate);
        }

        $isUnlocked = $user !== null && $user->hasSectionAccess($level);
        $totalSeconds = $this->preview->totalSeconds();
        $remainingSeconds = $isUnlocked ? $totalSeconds : $this->preview->remainingSeconds($request);

        view()->share([
            'sectionLevel' => $level,
            'sectionSlug' => $slug,
            'sectionLabel' => CertificationLevel::label($level),
            'sectionDescription' => CertificationLevel::descriptions()[$level],
            'sectionHeaderTag' => CertificationLevel::headerTag($level),
            'sectionPracticeHeadline' => CertificationLevel::practiceHeadline($level),
            'platformSections' => collect(CertificationLevel::all())->map(fn (string $l) => [
                'level' => $l,
                'slug' => CertificationLevel::slug($l),
                'label' => CertificationLevel::label($l),
                'active' => $l === $level,
            ]),
            'examCountdown' => $examCountdown,
            'previewTimer' => [
                'href' => $isUnlocked
                    ? route('platform.welcome', $slug)
                    : route('platform.paywall', $slug),
                'isUnlocked' => $isUnlocked,
                'remainingSeconds' => $remainingSeconds,
                'totalSeconds' => $totalSeconds,
                'remainingMinutes' => $isUnlocked ? null : $this->preview->remainingMinutes($request),
                'expiresAt' => $isUnlocked
                    ? null
                    : $this->preview->previewExpiresAt($request)->toIso8601String(),
                'symbol' => $isUnlocked ? '✓' : '◷',
                'ariaLabel' => $isUnlocked
                    ? 'Full Access — open your welcome page'
                    : 'Preview time remaining — view unlock options',
            ],
        ]);

        return $next($request);
    }
}
