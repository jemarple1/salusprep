<?php

namespace App\Http\Middleware;

use App\Services\PreviewAccessService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePreviewAccess
{
    public function __construct(private PreviewAccessService $preview) {}

    public function handle(Request $request, Closure $next): Response
    {
        $level = $request->attributes->get('certification_level');

        if (! is_string($level) || $level === '') {
            return $next($request);
        }

        if ($this->preview->isUnlocked($request, $level)) {
            return $next($request);
        }

        if ($this->preview->requiresPaywall($request, $level)) {
            $slug = $request->attributes->get('section_slug');

            return redirect()->route('platform.paywall', $slug);
        }

        return $next($request);
    }
}
