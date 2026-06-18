<?php

namespace App\Services;

use App\Models\Question;
use App\Support\CertificationLevel;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class QuestionImportService
{
    /** @return array{imported: int, updated: int, skipped: int} */
    public function importRows(array $rows): array
    {
        $imported = 0;
        $updated = 0;
        $skipped = 0;

        foreach ($rows as $index => $row) {
            try {
                $data = $this->normalizeRow($row, $index);
            } catch (InvalidArgumentException) {
                $skipped++;

                continue;
            }

            $question = Question::query()->updateOrCreate(
                ['source_key' => $data['source_key']],
                $data['attributes'],
            );

            if ($question->wasRecentlyCreated) {
                $imported++;
            } else {
                $updated++;
            }
        }

        return compact('imported', 'updated', 'skipped');
    }

    /**
     * @param  array<string, mixed>  $row
     * @return array{source_key: string, attributes: array<string, mixed>}
     */
    private function normalizeRow(array $row, int $index): array
    {
        $validator = Validator::make($row, [
            'source_key' => ['required', 'string', 'max:120'],
            'certification_level' => ['required', 'string'],
            'category' => ['required', 'string', 'max:100'],
            'difficulty' => ['required', 'integer', 'between:1,5'],
            'stem' => ['required', 'string'],
            'option_a' => ['required', 'string'],
            'option_b' => ['required', 'string'],
            'option_c' => ['required', 'string'],
            'option_d' => ['required', 'string'],
            'correct_option' => ['required', 'in:A,B,C,D'],
            'explanation' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            throw new InvalidArgumentException('Invalid row at index '.$index.': '.$validator->errors()->first());
        }

        $data = $validator->validated();
        $level = $this->normalizeLevel((string) $data['certification_level']);

        if (! CertificationLevel::isValid($level)) {
            throw new InvalidArgumentException('Invalid certification_level at index '.$index);
        }

        $correct = strtoupper((string) $data['correct_option']);
        $options = [
            'A' => trim((string) $data['option_a']),
            'B' => trim((string) $data['option_b']),
            'C' => trim((string) $data['option_c']),
            'D' => trim((string) $data['option_d']),
        ];

        if ($options[$correct] === '') {
            throw new InvalidArgumentException('Empty correct option text at index '.$index);
        }

        return [
            'source_key' => trim((string) $data['source_key']),
            'attributes' => [
                'certification_level' => $level,
                'category' => trim((string) $data['category']),
                'difficulty' => (int) $data['difficulty'],
                'initial_difficulty' => (int) $data['difficulty'],
                'stem' => trim((string) $data['stem']),
                'option_a' => $options['A'],
                'option_b' => $options['B'],
                'option_c' => $options['C'],
                'option_d' => $options['D'],
                'correct_option' => $correct,
                'explanation' => isset($data['explanation']) ? trim((string) $data['explanation']) : null,
            ],
        ];
    }

    private function normalizeLevel(string $level): string
    {
        $level = strtolower(trim($level));

        if (CertificationLevel::isValid($level)) {
            return $level;
        }

        $fromSlug = CertificationLevel::fromSlug($level);

        return $fromSlug ?? $level;
    }
}
