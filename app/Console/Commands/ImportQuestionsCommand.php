<?php

namespace App\Console\Commands;

use App\Services\QuestionImportService;
use Illuminate\Console\Command;

class ImportQuestionsCommand extends Command
{
    protected $signature = 'questions:import {path : Path to CSV or JSONL file}';

    protected $description = 'Import or update questions from a CSV/JSONL spreadsheet export';

    public function handle(QuestionImportService $import): int
    {
        $path = (string) $this->argument('path');

        if (! is_file($path)) {
            $this->error("File not found: {$path}");

            return self::FAILURE;
        }

        $rows = str_ends_with(strtolower($path), '.jsonl')
            ? $this->readJsonl($path)
            : $this->readCsv($path);

        if ($rows === []) {
            $this->error('No rows found in file.');

            return self::FAILURE;
        }

        $result = $import->importRows($rows);

        $this->info(sprintf(
            'Import complete: %d created, %d updated, %d skipped.',
            $result['imported'],
            $result['updated'],
            $result['skipped'],
        ));

        return self::SUCCESS;
    }

    /** @return list<array<string, mixed>> */
    private function readCsv(string $path): array
    {
        $handle = fopen($path, 'r');

        if ($handle === false) {
            return [];
        }

        $headers = fgetcsv($handle);

        if ($headers === false) {
            fclose($handle);

            return [];
        }

        $headers = array_map(fn ($header) => strtolower(trim((string) $header)), $headers);
        $rows = [];

        while (($data = fgetcsv($handle)) !== false) {
            if (count(array_filter($data, fn ($value) => trim((string) $value) !== '')) === 0) {
                continue;
            }

            $row = [];

            foreach ($headers as $index => $header) {
                $row[$header] = $data[$index] ?? null;
            }

            $rows[] = $row;
        }

        fclose($handle);

        return $rows;
    }

    /** @return list<array<string, mixed>> */
    private function readJsonl(string $path): array
    {
        $rows = [];

        foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            $decoded = json_decode($line, true);

            if (is_array($decoded)) {
                $rows[] = $decoded;
            }
        }

        return $rows;
    }
}
