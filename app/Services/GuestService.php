<?php

namespace App\Services;

use App\Models\ExamSession;
use App\Models\GuestSectionProgress;
use App\Models\SectionAccess;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GuestService
{
    public const SESSION_KEY = 'guest_token';

    public function token(Request $request): string
    {
        $token = $request->session()->get(self::SESSION_KEY);

        if (! is_string($token) || $token === '') {
            $token = (string) Str::uuid();
            $request->session()->put(self::SESSION_KEY, $token);
        }

        return $token;
    }

    public function progress(string $guestToken, string $certificationLevel): GuestSectionProgress
    {
        return GuestSectionProgress::firstOrCreate(
            [
                'guest_token' => $guestToken,
                'certification_level' => $certificationLevel,
            ],
            ['free_questions_used' => 0],
        );
    }

    public function activeExamSession(string $guestToken, string $certificationLevel): ?ExamSession
    {
        return ExamSession::query()
            ->where('guest_token', $guestToken)
            ->whereNull('user_id')
            ->where('certification_level', $certificationLevel)
            ->whereIn('status', [ExamSession::STATUS_IN_PROGRESS, ExamSession::STATUS_PAYWALL])
            ->latest()
            ->first();
    }

    public function mergeIntoUser(Request $request, User $user): void
    {
        $guestToken = $request->session()->get(self::SESSION_KEY);

        if (! is_string($guestToken) || $guestToken === '') {
            return;
        }

        foreach (GuestSectionProgress::where('guest_token', $guestToken)->get() as $guestProgress) {
            $access = SectionAccess::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'certification_level' => $guestProgress->certification_level,
                ],
                ['free_questions_used' => 0],
            );

            if ($guestProgress->free_questions_used > $access->free_questions_used) {
                $access->update(['free_questions_used' => $guestProgress->free_questions_used]);
            }
        }

        ExamSession::query()
            ->where('guest_token', $guestToken)
            ->whereNull('user_id')
            ->update([
                'user_id' => $user->id,
                'guest_token' => null,
            ]);

        GuestSectionProgress::where('guest_token', $guestToken)->delete();
    }
}
