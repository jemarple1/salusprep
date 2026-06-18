<?php

namespace App\Console\Commands;

use App\Services\QuestionImportService;
use Database\Seeders\QuestionBankGenerator;
use Illuminate\Console\Command;

class SeedQuestionBankCommand extends Command
{
    protected $signature = 'questions:seed-bank {--target=500 : Questions per platform}';

    protected $description = 'Generate and import additional questions to reach the target bank size per platform';

    public function handle(QuestionImportService $import, QuestionBankGenerator $generator): int
    {
        $target = max(1, (int) $this->option('target'));
        $rows = $generator->missingRows($target);

        if ($rows === []) {
            $this->info('All platforms already meet the target bank size.');

            return self::SUCCESS;
        }

        $result = $import->importRows($rows);

        $this->info(sprintf(
            'Question bank seed complete: %d created, %d updated, %d skipped.',
            $result['imported'],
            $result['updated'],
            $result['skipped'],
        ));

        return self::SUCCESS;
    }
}
