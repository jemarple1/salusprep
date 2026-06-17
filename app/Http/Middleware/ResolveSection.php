<?php

namespace App\Http\Middleware;

use App\Support\CertificationLevel;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolveSection
{
    public function handle(Request $request, Closure $next): Response
    {
        $slug = $request->route('section');

        if (! is_string($slug) || ! CertificationLevel::isValidSlug($slug)) {
            abort(404);
        }

        $level = CertificationLevel::fromSlug($slug);

        $request->attributes->set('certification_level', $level);
        $request->attributes->set('section_slug', $slug);

        view()->share([
            'sectionLevel' => $level,
            'sectionSlug' => $slug,
            'sectionLabel' => CertificationLevel::label($level),
            'sectionDescription' => CertificationLevel::descriptions()[$level],
            'platformSections' => collect(CertificationLevel::all())->map(fn (string $l) => [
                'level' => $l,
                'slug' => CertificationLevel::slug($l),
                'label' => CertificationLevel::label($l),
                'active' => $l === $level,
            ]),
        ]);

        return $next($request);
    }
}
