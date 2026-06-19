<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LogSnagService
{
    public function notifySignup(User $user): void
    {
        $token = config('services.logsnag.token');

        if (! is_string($token) || $token === '') {
            return;
        }

        $description = $user->email;

        if ($user->signup_country_name) {
            $description .= ' · '.$user->signup_country_name;
        }

        $this->track(
            event: (string) config('services.logsnag.signup_event', 'new sign up'),
            description: $description,
            userId: (string) $user->id,
            tags: array_filter([
                'name' => $user->name,
                'email' => $user->email,
                'country' => $user->signup_country_name,
            ]),
        );
    }

    /** @param  array<string, string|null>  $tags */
    public function track(string $event, ?string $description = null, ?string $userId = null, array $tags = []): void
    {
        $token = config('services.logsnag.token');

        if (! is_string($token) || $token === '') {
            return;
        }

        $payload = array_filter([
            'project' => config('services.logsnag.project'),
            'channel' => config('services.logsnag.channel', 'salusprep'),
            'event' => $event,
            'description' => $description,
            'user_id' => $userId,
            'icon' => config('services.logsnag.icon', '🚑'),
            'notify' => true,
            'tags' => $tags !== [] ? $tags : null,
        ], fn ($value) => $value !== null && $value !== '');

        try {
            $response = Http::withToken($token)
                ->acceptJson()
                ->post('https://api.logsnag.com/v1/log', $payload);

            if (! $response->successful()) {
                Log::warning('LogSnag track failed.', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            }
        } catch (\Throwable $exception) {
            Log::warning('LogSnag track exception.', [
                'message' => $exception->getMessage(),
            ]);
        }
    }
}
