<?php

namespace App\Services;

use Carbon\CarbonInterface;
use Illuminate\Http\Request;

class PreviewAccessService
{
    public const MINUTES_KEY = 'preview_minutes_limit';

    public const LEGACY_ACTIONS_KEY = 'preview_actions_limit';

    public const DEFAULT_MINUTES = 20;

    public const STUDY_PASS_ACTIONS_REQUIRED = 3;

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

    public function previewStartedAt(Request $request, string $certificationLevel): CarbonInterface
    {
        return $this->guests->previewStartedAt($request, $certificationLevel);
    }

    public function previewExpiresAt(Request $request, string $certificationLevel): CarbonInterface
    {
        return $this->previewStartedAt($request, $certificationLevel)->copy()->addMinutes($this->minutesLimit());
    }

    public function hasAccess(Request $request, string $certificationLevel): bool
    {
        if ($this->isUnlocked($request, $certificationLevel)) {
            return true;
        }

        return now()->lt($this->previewExpiresAt($request, $certificationLevel));
    }

    public function requiresPaywall(Request $request, string $certificationLevel): bool
    {
        return ! $this->hasAccess($request, $certificationLevel);
    }

    /**
     * Records a preview action and returns true when the preview window has ended.
     */
    public function recordAction(Request $request, string $certificationLevel): bool
    {
        if ($this->isUnlocked($request, $certificationLevel)) {
            return false;
        }

        $this->guests->incrementPreviewActions($request, $certificationLevel);

        return $this->requiresPaywall($request, $certificationLevel);
    }

    public function previewActionsUsed(Request $request, string $certificationLevel): int
    {
        if ($this->isUnlocked($request, $certificationLevel)) {
            return 0;
        }

        return $this->guests->previewActionsUsed($request, $certificationLevel);
    }

    public function requiresStudyClub(Request $request, string $certificationLevel): bool
    {
        if ($this->isUnlocked($request, $certificationLevel)) {
            return false;
        }

        if (! $this->hasAccess($request, $certificationLevel)) {
            return false;
        }

        if ($this->previewActionsUsed($request, $certificationLevel) < self::STUDY_PASS_ACTIONS_REQUIRED) {
            return false;
        }

        return ! app(StudyClubService::class)->hasJoined($request);
    }

    public function remainingMinutes(Request $request, string $certificationLevel): int
    {
        $seconds = $this->remainingSeconds($request, $certificationLevel);

        return max(0, (int) ceil($seconds / 60));
    }

    public function remainingSeconds(Request $request, string $certificationLevel): int
    {
        if ($this->isUnlocked($request, $certificationLevel)) {
            return 0;
        }

        $expiresAt = $this->previewExpiresAt($request, $certificationLevel);

        if (now()->gte($expiresAt)) {
            return 0;
        }

        return max(0, (int) now()->diffInSeconds($expiresAt, false));
    }

    public function expiresAt(Request $request, string $certificationLevel): ?CarbonInterface
    {
        if ($this->isUnlocked($request, $certificationLevel)) {
            return null;
        }

        return $this->previewExpiresAt($request, $certificationLevel);
    }

    public function totalSeconds(): int
    {
        return $this->minutesLimit() * 60;
    }

    public function progressPercent(Request $request, string $certificationLevel): float
    {
        $total = $this->totalSeconds();

        if ($total <= 0) {
            return 0;
        }

        return min(100, max(0, ($this->remainingSeconds($request, $certificationLevel) / $total) * 100));
    }
}
