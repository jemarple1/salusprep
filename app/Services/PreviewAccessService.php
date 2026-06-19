<?php

namespace App\Services;

use App\Models\SectionAccess;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;

class PreviewAccessService
{
    public const LIMIT_KEY = 'preview_actions_limit';

    public const DEFAULT_LIMIT = 25;

    public function __construct(private GuestService $guests) {}

    public function limit(): int
    {
        return Setting::getInt(self::LIMIT_KEY, self::DEFAULT_LIMIT);
    }

    public function isUnlocked(Request $request, string $certificationLevel): bool
    {
        $user = $request->user();

        return $user !== null && $user->hasSectionAccess($certificationLevel);
    }

    public function actionsUsed(Request $request, string $certificationLevel): int
    {
        $user = $request->user();

        if ($user !== null) {
            return $user->sectionAccessFor($certificationLevel)?->preview_actions_used ?? 0;
        }

        return $this->guests->progressForRequest($request, $certificationLevel)->preview_actions_used;
    }

    public function hasAccess(Request $request, string $certificationLevel): bool
    {
        if ($this->isUnlocked($request, $certificationLevel)) {
            return true;
        }

        return $this->actionsUsed($request, $certificationLevel) < $this->limit();
    }

    public function requiresPaywall(Request $request, string $certificationLevel): bool
    {
        return ! $this->hasAccess($request, $certificationLevel);
    }

    /**
     * Record one preview action for quiz answers, exercise checks, and flashcard advances.
     * Returns true when the preview limit was reached or already exceeded.
     */
    public function recordAction(Request $request, string $certificationLevel): bool
    {
        if ($this->isUnlocked($request, $certificationLevel)) {
            return false;
        }

        $limit = $this->limit();
        $user = $request->user();

        if ($user !== null) {
            $access = SectionAccess::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'certification_level' => $certificationLevel,
                ],
                ['preview_actions_used' => 0],
            );

            if ($access->preview_actions_used >= $limit) {
                return true;
            }

            $access->increment('preview_actions_used');

            return $access->fresh()->preview_actions_used >= $limit;
        }

        $progress = $this->guests->progressForRequest($request, $certificationLevel);

        if ($progress->preview_actions_used >= $limit) {
            return true;
        }

        $progress->increment('preview_actions_used');

        return $progress->fresh()->preview_actions_used >= $limit;
    }

    public function mergeGuestProgressIntoUser(User $user, string $deviceId): void
    {
        foreach (\App\Models\GuestSectionProgress::where('device_id', $deviceId)->get() as $guestProgress) {
            $access = SectionAccess::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'certification_level' => $guestProgress->certification_level,
                ],
                ['preview_actions_used' => 0],
            );

            if ($guestProgress->preview_actions_used > $access->preview_actions_used) {
                $access->update(['preview_actions_used' => $guestProgress->preview_actions_used]);
            }
        }
    }
}
