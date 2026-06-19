<?php

namespace Tests\Unit;

use App\Support\QuestionOptionRandomizer;
use Tests\TestCase;

class QuestionOptionRandomizerTest extends TestCase
{
    public function test_redistributes_correct_answer_to_stable_target_letter(): void
    {
        $row = [
            'source_key' => 'paramedic-bulk-001',
            'option_a' => 'Correct answer',
            'option_b' => 'Wrong 1',
            'option_c' => 'Wrong 2',
            'option_d' => 'Wrong 3',
            'correct_option' => 'A',
        ];

        $first = QuestionOptionRandomizer::redistribute($row);
        $second = QuestionOptionRandomizer::redistribute($row);

        $this->assertSame($first['correct_option'], $second['correct_option']);
        $this->assertNotSame('A', $first['correct_option']);
        $this->assertSame('Correct answer', $first['option_'.strtolower($first['correct_option'])]);
    }

    public function test_preserves_all_option_texts(): void
    {
        $row = [
            'source_key' => 'nclex_pn-bulk-042',
            'option_a' => 'Alpha',
            'option_b' => 'Bravo',
            'option_c' => 'Charlie',
            'option_d' => 'Delta',
            'correct_option' => 'A',
        ];

        $shuffled = QuestionOptionRandomizer::redistribute($row);
        $texts = [
            $shuffled['option_a'],
            $shuffled['option_b'],
            $shuffled['option_c'],
            $shuffled['option_d'],
        ];

        sort($texts);

        $this->assertSame(['Alpha', 'Bravo', 'Charlie', 'Delta'], $texts);
    }

    public function test_spreads_correct_letters_across_a_batch(): void
    {
        $distribution = ['A' => 0, 'B' => 0, 'C' => 0, 'D' => 0];

        for ($i = 1; $i <= 400; $i++) {
            $shuffled = QuestionOptionRandomizer::redistribute([
                'source_key' => 'paramedic-bulk-'.str_pad((string) $i, 3, '0', STR_PAD_LEFT),
                'option_a' => 'Correct',
                'option_b' => 'Wrong 1',
                'option_c' => 'Wrong 2',
                'option_d' => 'Wrong 3',
                'correct_option' => 'A',
            ]);

            $distribution[$shuffled['correct_option']]++;
        }

        foreach ($distribution as $count) {
            $this->assertGreaterThanOrEqual(70, $count);
            $this->assertLessThanOrEqual(130, $count);
        }
    }
}
