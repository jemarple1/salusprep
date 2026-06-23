<?php

namespace App\Console\Commands;

use App\Models\SectionAccess;
use App\Services\DailyStudyEmailService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class SendDailyStudyEmails extends Command
{
    protected $signature = 'study:send-daily-emails {--date= : Send for a specific Y-m-d date (testing)}';

    protected $description = 'Send daily study checklist emails to unlocked users at 9am Eastern';

    public function handle(DailyStudyEmailService $emails): int
    {
        $timezone = config('daily_study_email.timezone', 'America/New_York');
        $onDate = $this->option('date')
            ? Carbon::parse($this->option('date'), $timezone)->startOfDay()
            : now($timezone)->startOfDay();

        $sent = 0;
        $skipped = 0;

        SectionAccess::query()
            ->whereNotNull('unlocked_at')
            ->with('user')
            ->orderBy('id')
            ->chunkById(100, function ($accesses) use ($emails, $onDate, &$sent, &$skipped) {
                foreach ($accesses as $access) {
                    $user = $access->user;

                    if ($user === null) {
                        $skipped++;

                        continue;
                    }

                    if ($emails->send($access, $user, $onDate)) {
                        $sent++;
                    } else {
                        $skipped++;
                    }
                }
            });

        $this->info("Daily study emails sent: {$sent} (skipped: {$skipped})");

        return self::SUCCESS;
    }
}
