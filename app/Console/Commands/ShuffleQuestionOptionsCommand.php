<?php

namespace App\Console\Commands;

use App\Models\Question;
use App\Support\CertificationLevel;
use App\Support\QuestionOptionRandomizer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ShuffleQuestionOptionsCommand extends Command
{
    protected $signature = 'questions:shuffle-options
                            {--level=* : Certification level(s) to update; defaults to all platforms}
                            {--write-banks : Rewrite question bank seed files}
                            {--database : Update questions already stored in the database}';

    protected $description = 'Redistribute correct answers evenly across A–D for question banks and/or the database';

    public function handle(): int
    {
        $levels = $this->resolveLevels();
        $writeBanks = (bool) $this->option('write-banks');
        $updateDatabase = (bool) $this->option('database') || (! $writeBanks && ! $this->option('database'));

        if (! $writeBanks && ! $updateDatabase) {
            $this->error('Nothing to do. Pass --write-banks and/or --database.');

            return self::FAILURE;
        }

        if ($writeBanks) {
            foreach ($levels as $level) {
                $this->rewriteBankFile($level);
            }
        }

        if ($updateDatabase) {
            $this->updateDatabaseQuestions($levels);
        }

        return self::SUCCESS;
    }

    /** @return list<string> */
    private function resolveLevels(): array
    {
        $requested = collect($this->option('level'))
            ->filter(fn ($level) => is_string($level) && $level !== '')
            ->map(function (string $level) {
                if (CertificationLevel::isValid($level)) {
                    return $level;
                }

                $fromSlug = CertificationLevel::fromSlug($level);

                return $fromSlug;
            })
            ->filter()
            ->unique()
            ->values()
            ->all();

        return $requested !== [] ? $requested : CertificationLevel::all();
    }

    private function rewriteBankFile(string $level): void
    {
        $path = $this->bankPath($level);

        if ($path === null || ! File::exists($path)) {
            $this->warn("No bank file found for {$level}.");

            return;
        }

        /** @var list<array<string, mixed>> $rows */
        $rows = require $path;
        $shuffled = array_map(
            fn (array $row) => QuestionOptionRandomizer::redistribute($row),
            $rows,
        );

        File::put($path, $this->exportPhpArray($shuffled));

        $distribution = $this->distributionForRows($shuffled);
        $this->info("Rewrote {$path} — correct options: ".$this->formatDistribution($distribution));
    }

    /** @param  list<string>  $levels */
    private function updateDatabaseQuestions(array $levels): void
    {
        $updated = 0;

        Question::query()
            ->whereIn('certification_level', $levels)
            ->orderBy('id')
            ->chunkById(100, function ($questions) use (&$updated) {
                foreach ($questions as $question) {
                    $row = QuestionOptionRandomizer::redistribute([
                        'source_key' => $question->source_key,
                        'option_a' => $question->option_a,
                        'option_b' => $question->option_b,
                        'option_c' => $question->option_c,
                        'option_d' => $question->option_d,
                        'correct_option' => $question->correct_option,
                    ]);

                    $question->update([
                        'option_a' => $row['option_a'],
                        'option_b' => $row['option_b'],
                        'option_c' => $row['option_c'],
                        'option_d' => $row['option_d'],
                        'correct_option' => $row['correct_option'],
                    ]);

                    $updated++;
                }
            });

        $this->info("Updated {$updated} database question(s).");

        foreach ($levels as $level) {
            $distribution = Question::query()
                ->where('certification_level', $level)
                ->selectRaw('correct_option, COUNT(*) as total')
                ->groupBy('correct_option')
                ->pluck('total', 'correct_option')
                ->all();

            if ($distribution !== []) {
                $this->line(CertificationLevel::label($level).': '.$this->formatDistribution($distribution));
            }
        }
    }

    private function bankPath(string $level): ?string
    {
        $filename = match ($level) {
            CertificationLevel::EMT_BASIC => 'emt_basic.php',
            CertificationLevel::EMT_ADVANCED => 'emt_advanced.php',
            CertificationLevel::PARAMEDIC => 'paramedic.php',
            CertificationLevel::NCLEX_PN => 'nclex_pn.php',
            default => null,
        };

        return $filename === null
            ? null
            : database_path('seeders/questionbanks/'.$filename);
    }

    /** @param  list<array<string, mixed>>  $rows */
    private function distributionForRows(array $rows): array
    {
        $distribution = ['A' => 0, 'B' => 0, 'C' => 0, 'D' => 0];

        foreach ($rows as $row) {
            $letter = strtoupper((string) ($row['correct_option'] ?? 'A'));
            $distribution[$letter] = ($distribution[$letter] ?? 0) + 1;
        }

        return $distribution;
    }

    /** @param  array<string, int>  $distribution */
    private function formatDistribution(array $distribution): string
    {
        return collect(['A', 'B', 'C', 'D'])
            ->map(fn (string $letter) => $letter.'='.($distribution[$letter] ?? 0))
            ->implode(', ');
    }

    /** @param  list<array<string, mixed>>  $rows */
    private function exportPhpArray(array $rows): string
    {
        $content = "<?php\n\nreturn [\n";

        foreach ($rows as $row) {
            $content .= "    [\n";

            foreach ($row as $key => $value) {
                $content .= '        '.var_export((string) $key, true).' => '.var_export($value, true).",\n";
            }

            $content .= "    ],\n";
        }

        $content .= "];\n";

        return $content;
    }
}
