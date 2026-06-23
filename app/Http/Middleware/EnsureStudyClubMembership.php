<?php

namespace App\Http\Middleware;

use App\Services\PreviewAccessService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureStudyClubMembership
{
    public function __construct(private PreviewAccessService $preview) {}

    public function handle(Request $request, Closure $next): Response
    {
        $level = $request->attributes->get('certification_level');

        if (! is_string($level) || $level === '') {
            return $next($request);
        }

        if (! $this->preview->requiresStudyClub($request, $level)) {
            return $next($request);
        }

        if ($request->isMethod('GET', 'HEAD')) {
            view()->share('requiresStudyClubJoin', true);

            return $next($request);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Join Study Pass for free to continue your preview.',
                'study_club_required' => true,
            ], 403);
        }

        return redirect()
            ->back()
            ->withInput()
            ->with('study_club_required', true);
    }
}
