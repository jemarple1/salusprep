<?php

require_once __DIR__.'/helpers.php';

$morseOptions = [
    'history' => [
        'label' => 'History of Falling',
        'options' => [
            0 => 'No — no fall in this admission or within the last 3 months.',
            25 => 'Yes — fell during this hospitalization or within the last 3 months.',
        ],
    ],
    'secondary' => [
        'label' => 'Secondary Diagnosis',
        'options' => [
            0 => 'No — no secondary diagnosis linked to fall risk.',
            15 => 'Yes — has at least one secondary diagnosis (e.g., HF, syncope, orthostatic hypotension, confusion).',
        ],
    ],
    'ambulatory' => [
        'label' => 'Ambulatory Aid',
        'options' => [
            0 => 'None, bed rest, or nurse assist — patient does not ambulate or is fully assisted by staff.',
            15 => 'Crutches, cane, or walker — uses a standard assistive device.',
            30 => 'Furniture — ambulates but relies on furniture or walls for support.',
        ],
    ],
    'iv' => [
        'label' => 'IV / Heparin Lock',
        'options' => [
            0 => 'No — no IV access or heparin lock present.',
            20 => 'Yes — has IV line, saline lock, or heparin lock in place.',
        ],
    ],
    'gait' => [
        'label' => 'Gait',
        'options' => [
            0 => 'Normal, bed rest, or immobile — normal gait or does not ambulate.',
            10 => 'Weak — stooped, short steps, or uses assistive device with unsteady pattern.',
            20 => 'Impaired — shuffling, unable to rise without help, or observed unsafe gait.',
        ],
    ],
    'mental' => [
        'label' => 'Mental Status',
        'options' => [
            0 => 'Oriented — patient knows own ability and calls for assistance.',
            15 => 'Overestimates or forgets limitations — impulsive, forgets to call for help, or misjudges ability.',
        ],
    ],
];

$banks = [
    [
        'title' => 'Independent post-op day 0',
        'scenario' => 'Ms. Allen, 52, is oriented after cholecystectomy. She has no history of recent falls, no comorbid fall-risk diagnoses, walks independently without devices, has no IV after PACU discharge, and calls before getting up.',
        'instruction' => 'Score each Morse Fall Scale factor described in the scenario.',
        'explanation' => 'All factors score 0 except none elevated — total 0 = low fall risk. Continue standard safety measures and teach to call for help after anesthesia.',
        'scores' => ['history' => 0, 'secondary' => 0, 'ambulatory' => 0, 'iv' => 0, 'gait' => 0, 'mental' => 0],
    ],
    [
        'title' => 'Repeat faller with UTI',
        'scenario' => 'Mr. Boone, 79, fell twice this admission. He has a UTI and dehydration as secondary diagnoses, shuffles barefoot to the bathroom using the rolling IV pole for balance, still has a peripheral IV, and tries to stand alone when urgent.',
        'instruction' => 'Apply Morse scoring to this repeat faller.',
        'explanation' => 'History yes (25), secondary yes (15), ambulatory furniture (30), IV yes (20), gait impaired shuffling (20), mental overestimates (15). Total 125 = high risk — implement high-risk fall protocol.',
        'scores' => ['history' => 25, 'secondary' => 15, 'ambulatory' => 30, 'iv' => 20, 'gait' => 20, 'mental' => 15],
    ],
    [
        'title' => 'Weak gait with walker',
        'scenario' => 'A patient with Parkinson disease uses a rolling walker, has no recent falls, takes antihypertensives (secondary diagnosis), no IV, walks with a stooped weak gait, and waits for staff when needed.',
        'instruction' => 'Score Morse factors for Parkinson disease with assistive device.',
        'explanation' => 'History no (0), secondary yes (15), ambulatory walker (15), IV no (0), gait weak (10), mental oriented (0). Total 40 = moderate risk — timed toileting and walker within reach.',
        'scores' => ['history' => 0, 'secondary' => 15, 'ambulatory' => 15, 'iv' => 0, 'gait' => 10, 'mental' => 0],
    ],
    [
        'title' => 'Confused with saline lock',
        'scenario' => 'An older adult with delirium from pneumonia has no documented falls in 3 months but has pneumonia as a secondary diagnosis, is bed rest only, a saline lock in the left forearm, immobile gait score, and repeatedly tries to climb out of bed.',
        'instruction' => 'Rate Morse fall-risk factors for delirium and immobility.',
        'explanation' => 'History no (0), secondary yes (15), ambulatory bed rest (0), IV yes (20), gait immobile (0), mental overestimates (15). Total 50 = moderate risk — bed alarm and frequent reorientation.',
        'scores' => ['history' => 0, 'secondary' => 15, 'ambulatory' => 0, 'iv' => 20, 'gait' => 0, 'mental' => 15],
    ],
    [
        'title' => 'Orthostatic hypotension',
        'scenario' => 'A patient with autonomic neuropathy fell at home last month. Secondary diagnosis includes orthostatic hypotension. He ambulates with a cane, has no IV, gait is weak with slow turns, and he acknowledges needing help standing.',
        'instruction' => 'Assign Morse scores for orthostatic hypotension and recent fall history.',
        'explanation' => 'History yes (25), secondary yes (15), ambulatory cane (15), IV no (0), gait weak (10), mental oriented (0). Total 65 = high risk — rise slowly, compression stockings, and supervised ambulation.',
        'scores' => ['history' => 25, 'secondary' => 15, 'ambulatory' => 15, 'iv' => 0, 'gait' => 10, 'mental' => 0],
    ],
    [
        'title' => 'Postpartum faint',
        'scenario' => 'A postpartum patient fainted once this admission after getting up quickly. Secondary diagnosis: anemia. She walks holding the crib and IV pole, has a running IV for antibiotics, gait appears normal when observed, and she forgets to call before walking.',
        'instruction' => 'Score Morse factors for a postpartum patient with syncope.',
        'explanation' => 'History yes (25), secondary yes (15), ambulatory furniture (30), IV yes (20), gait normal when steady (0), mental forgets limitations (15). Total 105 = high risk — teach to call for help and slow position changes.',
        'scores' => ['history' => 25, 'secondary' => 15, 'ambulatory' => 30, 'iv' => 20, 'gait' => 0, 'mental' => 15],
    ],
    [
        'title' => 'Bedbound stroke patient',
        'scenario' => 'After a large stroke the patient is bedbound with a heparin lock, no falls in the last year, secondary diagnosis of CVA, does not ambulate, gait scored as immobile, and is oriented but impulsive when transferring with one-person assist.',
        'instruction' => 'Apply Morse Fall Scale to a non-ambulatory stroke patient.',
        'explanation' => 'History no (0), secondary yes (15), ambulatory bed rest (0), IV yes (20), gait immobile (0), mental overestimates during transfers (15). Total 50 = moderate risk despite immobility — focus on safe transfers.',
        'scores' => ['history' => 0, 'secondary' => 15, 'ambulatory' => 0, 'iv' => 20, 'gait' => 0, 'mental' => 15],
    ],
    [
        'title' => 'Chemotherapy neuropathy',
        'scenario' => 'A oncology patient fell last week at home. Secondary diagnoses include peripheral neuropathy and thrombocytopenia. She uses furniture to walk to the bathroom, has a central line, gait is impaired with wide base, and she overestimates stamina.',
        'instruction' => 'Score Morse factors for neuropathy and recent community fall.',
        'explanation' => 'History yes (25), secondary yes (15), ambulatory furniture (30), IV yes (20), gait impaired (20), mental overestimates (15). Total 125 = high risk — non-skid footwear and supervised toileting.',
        'scores' => ['history' => 25, 'secondary' => 15, 'ambulatory' => 30, 'iv' => 20, 'gait' => 20, 'mental' => 15],
    ],
    [
        'title' => 'Low-risk med-surg teen',
        'scenario' => 'A 17-year-old admitted for appendectomy has no fall history, no secondary fall-risk diagnoses, ambulates independently without aids, no IV after line removal, normal steady gait, and asks for help before walking post-op.',
        'instruction' => 'Rate Morse fall-risk factors for a low-risk surgical adolescent.',
        'explanation' => 'All six factors score 0 — total 0 = low risk. Standard fall precautions and post-anesthesia teaching remain appropriate.',
        'scores' => ['history' => 0, 'secondary' => 0, 'ambulatory' => 0, 'iv' => 0, 'gait' => 0, 'mental' => 0],
    ],
    [
        'title' => 'Night shift impulsive elder',
        'scenario' => 'An 85-year-old with dementia fell yesterday evening. Secondary diagnosis: dementia. He wanders holding chair backs for support, has a saline lock, shuffling impaired gait, and attempts bathroom trips alone at night without calling.',
        'instruction' => 'Complete Morse scoring for nocturnal wandering with dementia.',
        'explanation' => 'History yes (25), secondary yes (15), ambulatory furniture (30), IV yes (20), gait impaired (20), mental overestimates (15). Total 125 = high risk — bed alarm, toileting schedule, and low bed position.',
        'scores' => ['history' => 25, 'secondary' => 15, 'ambulatory' => 30, 'iv' => 20, 'gait' => 20, 'mental' => 15],
    ],
];

return nclex_levels(array_map(
    fn (array $bank) => function (int $level, int $index) use ($bank, $morseOptions): array {
        $factorKeys = ['history', 'secondary', 'ambulatory', 'iv', 'gait', 'mental'];
        $count = match ($level) {
            1 => 3,
            2, 3 => 4,
            4 => 5,
            5 => 6,
            default => 6,
        };

        $selectedKeys = array_slice($factorKeys, 0, $count);
        $subscales = [];

        foreach ($selectedKeys as $key) {
            $subscales[$key] = [
                'label' => $morseOptions[$key]['label'],
                'options' => $morseOptions[$key]['options'],
                'correct' => $bank['scores'][$key],
            ];
        }

        $scenario = nclex_enrich([
            'title' => $bank['title'],
            'scenario' => $bank['scenario'],
            'instruction' => $bank['instruction'],
            'explanation' => $bank['explanation'],
        ], $level, $index);

        $scenario['subscales'] = $subscales;
        $scenario['total'] = array_sum(array_map(fn (string $key): int => $bank['scores'][$key], $selectedKeys));

        return $scenario;
    },
    $banks,
));
