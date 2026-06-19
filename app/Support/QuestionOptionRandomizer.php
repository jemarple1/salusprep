<?php

namespace App\Support;

class QuestionOptionRandomizer
{
    private const LETTERS = ['A', 'B', 'C', 'D'];

    /**
     * Move the correct answer to a stable pseudo-random option letter per source_key.
     *
     * @param  array<string, mixed>  $row
     * @return array<string, mixed>
     */
    public static function redistribute(array $row): array
    {
        $currentCorrect = strtoupper((string) ($row['correct_option'] ?? 'A'));

        if (! in_array($currentCorrect, self::LETTERS, true)) {
            return $row;
        }

        $optionsByLetter = [
            'A' => (string) ($row['option_a'] ?? ''),
            'B' => (string) ($row['option_b'] ?? ''),
            'C' => (string) ($row['option_c'] ?? ''),
            'D' => (string) ($row['option_d'] ?? ''),
        ];

        $correctText = $optionsByLetter[$currentCorrect];
        $wrongTexts = [];

        foreach (self::LETTERS as $letter) {
            if ($letter !== $currentCorrect) {
                $wrongTexts[] = $optionsByLetter[$letter];
            }
        }

        $targetCorrect = self::LETTERS[self::targetIndex((string) ($row['source_key'] ?? ''))];
        $wrongTexts = self::seededShuffle($wrongTexts, (string) ($row['source_key'] ?? '').'-opts');

        $newOptions = [];
        $wrongIndex = 0;

        foreach (self::LETTERS as $letter) {
            if ($letter === $targetCorrect) {
                $newOptions[$letter] = $correctText;
            } else {
                $newOptions[$letter] = $wrongTexts[$wrongIndex++];
            }
        }

        return array_merge($row, [
            'option_a' => $newOptions['A'],
            'option_b' => $newOptions['B'],
            'option_c' => $newOptions['C'],
            'option_d' => $newOptions['D'],
            'correct_option' => $targetCorrect,
        ]);
    }

    /** @param  list<string>  $items */
    private static function seededShuffle(array $items, string $seed): array
    {
        $items = array_values($items);

        if ($items === []) {
            return $items;
        }

        mt_srand(crc32($seed));

        for ($i = count($items) - 1; $i > 0; $i--) {
            $j = mt_rand(0, $i);
            [$items[$i], $items[$j]] = [$items[$j], $items[$i]];
        }

        mt_srand();

        return $items;
    }

    private static function targetIndex(string $sourceKey): int
    {
        if ($sourceKey === '') {
            return 0;
        }

        return (int) (sprintf('%u', crc32($sourceKey)) % 4);
    }
}
