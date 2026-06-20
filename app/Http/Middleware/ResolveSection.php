<?php

namespace App\Http\Middleware;

use App\Models\SectionAccess;
use App\Services\ExamCountdownService;
use App\Support\CertificationLevel;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolveSection
{
    public function __construct(private ExamCountdownService $countdown) {}

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

        view()->share([
            'sectionLevel' => $level,
            'sectionSlug' => $slug,
            'sectionLabel' => CertificationLevel::label($level),
            'sectionDescription' => CertificationLevel::descriptions()[$level],
            'sectionHeaderTag' => CertificationLevel::headerTag($level),
            'sectionPracticeHeadline' => CertificationLevel::practiceHeadline($level),
            'platformSwitcherHint' => CertificationLevel::platformSwitcherHint($level),
            'platformSections' => collect(CertificationLevel::all())->map(fn (string $l) => [
                'level' => $l,
                'slug' => CertificationLevel::slug($l),
                'label' => CertificationLevel::label($l),
                'active' => $l === $level,
            ]),
            'examCountdown' => $examCountdown,
        ]);

        return $next($request);
    }
}
