<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Support\CertificationLevel;

class QuestionBankGenerator
{
    /** @return list<array<string, mixed>> */
    public function missingRows(int $targetPerPlatform = 500): array
    {
        $rows = [];

        foreach (CertificationLevel::all() as $level) {
            $existing = Question::query()->where('certification_level', $level)->count();
            $needed = max(0, $targetPerPlatform - $existing);

            if ($needed === 0) {
                continue;
            }

            $bank = $this->bankForLevel($level);
            $available = array_values(array_filter(
                $bank,
                fn (array $row) => ! Question::query()->where('source_key', $row['source_key'])->exists(),
            ));

            $rows = array_merge($rows, array_slice($available, 0, $needed));
        }

        return $rows;
    }

    /** @return list<array<string, mixed>> */
    private function bankForLevel(string $level): array
    {
        $path = match ($level) {
            CertificationLevel::EMT_BASIC => __DIR__.'/questionbanks/emt_basic.php',
            CertificationLevel::EMT_ADVANCED => __DIR__.'/questionbanks/emt_advanced.php',
            CertificationLevel::PARAMEDIC => __DIR__.'/questionbanks/paramedic.php',
            CertificationLevel::NCLEX_PN => __DIR__.'/questionbanks/nclex_pn.php',
            default => null,
        };

        if ($path === null || ! is_file($path)) {
            return [];
        }

        /** @var list<array<string, mixed>> $rows */
        $rows = require $path;

        return $rows;
    }
}
