<?php

namespace App\Http\Middleware;

use App\Services\GuestService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePreviewDevice
{
    public function __construct(private GuestService $guests) {}

    public function handle(Request $request, Closure $next): Response
    {
        $this->guests->deviceId($request);

        return $next($request);
    }
}
