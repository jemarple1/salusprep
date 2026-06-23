<?php

namespace App\Services;

use App\Models\StudyClubMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StudyClubService
{
    public function __construct(private GuestService $guests) {}

    public function hasJoined(Request $request): bool
    {
        $user = $request->user();

        if ($user instanceof User) {
            if ($this->activeMembershipQuery()
                ->where('user_id', $user->id)
                ->exists()) {
                return true;
            }

            if ($this->activeMembershipQuery()
                ->where('email', $user->email)
                ->exists()) {
                return true;
            }
        }

        $deviceId = $this->guests->deviceId($request);

        return $this->activeMembershipQuery()
            ->where('device_id', $deviceId)
            ->exists();
    }

    public function join(Request $request, string $email, ?string $certificationLevel = null): StudyClubMember
    {
        $email = strtolower(trim($email));
        $deviceId = $this->guests->deviceId($request);
        $user = $request->user();

        $existing = StudyClubMember::query()
            ->where('email', $email)
            ->first();

        if ($existing !== null) {
            $existing->fill([
                'device_id' => $deviceId,
                'user_id' => $user?->id ?? $existing->user_id,
                'certification_level' => $certificationLevel ?? $existing->certification_level,
                'joined_at' => now(),
                'unsubscribed_at' => null,
            ]);
            $existing->save();

            if ($user instanceof User) {
                $user->forceFill(['marketing_emails_opt_in' => true])->save();
            }

            return $existing;
        }

        $member = StudyClubMember::query()->create([
            'email' => $email,
            'device_id' => $deviceId,
            'user_id' => $user?->id,
            'certification_level' => $certificationLevel,
            'joined_at' => now(),
            'unsubscribe_token' => Str::random(48),
        ]);

        if ($user instanceof User) {
            $user->forceFill(['marketing_emails_opt_in' => true])->save();
        }

        return $member;
    }

    public function unsubscribe(string $token): ?StudyClubMember
    {
        $member = StudyClubMember::query()
            ->where('unsubscribe_token', $token)
            ->first();

        if ($member === null || ! $member->isActive()) {
            return null;
        }

        $member->update(['unsubscribed_at' => now()]);

        if ($member->user_id !== null) {
            User::query()
                ->whereKey($member->user_id)
                ->update(['marketing_emails_opt_in' => false]);
        }

        return $member->fresh();
    }

    public function linkUserByEmail(User $user): void
    {
        StudyClubMember::query()
            ->where('email', $user->email)
            ->whereNull('user_id')
            ->update(['user_id' => $user->id]);
    }

    public function emailForRequest(Request $request): ?string
    {
        return $this->activeMembershipQuery()
            ->where('device_id', $this->guests->deviceId($request))
            ->orderByDesc('joined_at')
            ->value('email');
    }

    public function syncDeviceMembership(Request $request, string $email, ?int $userId = null): void
    {
        $email = strtolower(trim($email));
        $deviceId = $this->guests->deviceId($request);

        $member = StudyClubMember::query()
            ->where('device_id', $deviceId)
            ->whereNull('unsubscribed_at')
            ->latest('joined_at')
            ->first();

        if ($member === null) {
            return;
        }

        $member->update([
            'email' => $email,
            'user_id' => $userId ?? $member->user_id,
        ]);
    }

    /** @return \Illuminate\Database\Eloquent\Builder<StudyClubMember> */
    private function activeMembershipQuery()
    {
        return StudyClubMember::query()->whereNull('unsubscribed_at');
    }
}
