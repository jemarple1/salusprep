<?php

namespace App\Services;

use Carbon\CarbonInterface;
use Illuminate\Http\Request;

class PreviewAccessService
{
    public const MINUTES_KEY = 'preview_minutes_limit';

    public const LEGACY_ACTIONS_KEY = 'preview_actions_limit';

    public const DEFAULT_MINUTES = 20;

    public function __construct(private GuestService $guests) {}

    public function minutesLimit(): int
    {
        $minutes = (int) \App\Models\Setting::getInt(self::MINUTES_KEY, 0);

        return $minutes > 0 ? $minutes : self::DEFAULT_MINUTES;
    }

    public function isUnlocked(Request $request, string $certificationLevel): bool
    {
        $user = $request->user();

        return $user !== null && $user->hasSectionAccess($certificationLevel);
    }

    public function previewStartedAt(Request $request): CarbonInterface
    {
        return $this->guests->previewStartedAt($request);
    }

    public function previewExpiresAt(Request $request): CarbonInterface
    {
        return $this->previewStartedAt($request)->copy()->addMinutes($this->minutesLimit());
    }

    public function hasAccess(Request $request, string $certificationLevel): bool
    {
        if ($this->isUnlocked($request, $certificationLevel)) {
            return true;
        }

        return now()->lt($this->previewExpiresAt($request));
    }

    public function requiresPaywall(Request $request, string $certificationLevel): bool
    {
        return ! $this->hasAccess($request, $certificationLevel);
    }

    /**
     * Returns true when the preview window has ended.
     */
    public function recordAction(Request $request, string $certificationLevel): bool
    {
        return $this->requiresPaywall($request, $certificationLevel);
    }

    public function remainingMinutes(Request $request): int
    {
        if (now()->gte($this->previewExpiresAt($request))) {
            return 0;
        }

        return max(0, (int) now()->diffInMinutes($this->previewExpiresAt($request), false));
    }
}
