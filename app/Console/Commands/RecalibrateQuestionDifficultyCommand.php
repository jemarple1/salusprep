<?php

namespace App\Console\Commands;

use App\Services\QuestionRecalibrationService;
use Illuminate\Console\Command;

class RecalibrateQuestionDifficultyCommand extends Command
{
    protected $signature = 'questions:recalibrate-difficulty';

    protected $description = 'Recalibrate question difficulty from platform-wide answer statistics';

    public function handle(QuestionRecalibrationService $recalibration): int
    {
        $result = $recalibration->recalibrate();

        $this->info(sprintf(
            'Recalibrated %d questions (%d unchanged or below minimum attempts, %d total in bank).',
            $result['updated'],
            $result['skipped'],
            $result['total'],
        ));

        return self::SUCCESS;
    }
}
