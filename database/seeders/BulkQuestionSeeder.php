<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Support\CertificationLevel;
use Illuminate\Database\Seeder;

class BulkQuestionSeeder extends Seeder
{
    public function run(): void
    {
        $generator = new QuestionBankGenerator;
        $rows = $generator->missingRows(500);

        if ($rows === []) {
            return;
        }

        app(\App\Services\QuestionImportService::class)->importRows($rows);
    }
}
