<?php

namespace App\Console\Commands;

use App\Models\SectionAccess;
use App\Models\User;
use App\Services\DailyStudyEmailService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class SendDailyStudyTestEmail extends Command
{
    protected $signature = 'study:send-daily-test-email
                            {--email= : Send to a user by email address}
                            {--user= : Send to a user by ID}
                            {--all : Send a test email for every unlocked section access}';

    protected $description = 'Send a test daily study checklist email (does not update send history)';

    public function handle(DailyStudyEmailService $emails): int
    {
        $timezone = config('daily_study_email.timezone', 'America/New_York');
        $onDate = now($timezone)->startOfDay();

        $targets = $this->resolveTargets();

        if ($targets === []) {
            $this->error('No recipients found. Use --email=, --user=, or --all.');

            return self::FAILURE;
        }

        $sent = 0;

        foreach ($targets as $target) {
            $access = $target['access'];
            $user = $target['user'];

            if ($emails->sendTest($access, $user, $onDate)) {
                $sent++;
                $this->line("Sent test daily email to {$user->email} ({$access->certification_level})");
            }
        }

        $this->info("Test daily study emails sent: {$sent}");

        return self::SUCCESS;
    }

    /** @return list<array{access: SectionAccess, user: User}> */
    private function resolveTargets(): array
    {
        if ($this->option('all')) {
            return SectionAccess::query()
                ->whereNotNull('unlocked_at')
                ->with('user')
                ->orderBy('id')
                ->get()
                ->filter(fn (SectionAccess $access) => $access->user !== null)
                ->map(fn (SectionAccess $access) => ['access' => $access, 'user' => $access->user])
                ->values()
                ->all();
        }

        $user = null;

        if ($email = $this->option('email')) {
            $user = User::query()->where('email', $email)->first();
        } elseif ($userId = $this->option('user')) {
            $user = User::query()->find($userId);
        }

        if ($user === null) {
            return [];
        }

        return SectionAccess::query()
            ->where('user_id', $user->id)
            ->whereNotNull('unlocked_at')
            ->orderBy('certification_level')
            ->get()
            ->map(fn (SectionAccess $access) => ['access' => $access, 'user' => $user])
            ->values()
            ->all();
    }
}
