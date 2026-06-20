<?php

require_once __DIR__.'/helpers.php';

$bradenOptions = [
    'sensory' => [
        'label' => 'Sensory Perception',
        'options' => [
            4 => 'No impairment — responds to verbal commands; no sensory deficit.',
            3 => 'Slightly limited — responds to verbal commands but cannot feel discomfort in one or two extremities.',
            2 => 'Very limited — responds only to painful stimuli; cannot communicate discomfort.',
            1 => 'Completely limited — unresponsive to painful stimuli due to diminished consciousness or sedation.',
        ],
    ],
    'moisture' => [
        'label' => 'Moisture',
        'options' => [
            4 => 'Rarely moist — skin is usually dry; linen changed at routine intervals.',
            3 => 'Occasionally moist — skin is occasionally moist, requiring an extra linen change about once a day.',
            2 => 'Very moist — skin is often but not always moist; linen changed at least once per shift.',
            1 => 'Constantly moist — perspiration, urine, or drainage keeps skin moist continuously.',
        ],
    ],
    'activity' => [
        'label' => 'Activity',
        'options' => [
            4 => 'Walks frequently — walks outside room at least twice daily and inside room every two hours.',
            3 => 'Walks occasionally — walks occasionally during day but for very short distances, with or without assistance.',
            2 => 'Chairfast — ability to walk severely limited; cannot bear own weight and must be assisted into chair or wheelchair.',
            1 => 'Bedfast — confined to bed.',
        ],
    ],
    'mobility' => [
        'label' => 'Mobility',
        'options' => [
            4 => 'Not limited — makes major and frequent changes in position without assistance.',
            3 => 'Slightly limited — makes frequent though slight changes in body or extremity position independently.',
            2 => 'Very limited — makes occasional slight changes in body or extremity position but unable to make frequent changes independently.',
            1 => 'Completely immobile — does not make even slight changes in body or extremity position without assistance.',
        ],
    ],
    'nutrition' => [
        'label' => 'Nutrition',
        'options' => [
            4 => 'Excellent — eats most of every meal; never refuses a meal; usually eats a total of four or more servings of meat and dairy products.',
            3 => 'Adequate — eats over half of most meals; eats a total of four servings of protein and dairy each day.',
            2 => 'Probably inadequate — rarely eats a complete meal; protein intake includes only three servings of meat or dairy per day.',
            1 => 'Very poor — never eats a complete meal; eats two servings or less of protein per day; takes fluids poorly.',
        ],
    ],
    'friction' => [
        'label' => 'Friction & Shear',
        'options' => [
            3 => 'No apparent problem — moves in bed and chair independently; maintains good position; lifts completely during transfer.',
            2 => 'Potential problem — moves feebly or requires minimum assistance; skin probably slides against sheets, chair, or other devices.',
            1 => 'Problem — requires moderate to maximum assistance in moving; frequent sliding; spasticity or contractures present.',
        ],
    ],
];

$banks = [
    [
        'title' => 'Alert with diabetic neuropathy',
        'scenario' => 'Mr. Chen, 74, is alert and oriented but has decreased sensation in both feet from peripheral neuropathy. He walks to the bathroom with a walker twice daily. Skin is dry; he eats most meals and repositions himself in bed.',
        'instruction' => 'Rate each Braden subscale based on the scenario.',
        'explanation' => 'Sensory is slightly limited (3) due to neuropathy. Moisture rarely moist (4), activity walks occasionally (3), mobility not limited (4), nutrition adequate (3), friction no problem (3). Total 20 = mild risk — implement basic prevention.',
        'scores' => ['sensory' => 3, 'moisture' => 4, 'activity' => 3, 'mobility' => 4, 'nutrition' => 3, 'friction' => 3],
    ],
    [
        'title' => 'Ventilated ICU patient',
        'scenario' => 'Ms. Ortiz is sedated on a ventilator after septic shock. She does not respond to voice or painful stimuli. Incontinence of urine keeps perineal skin constantly moist. She is bedfast and does not change position without staff.',
        'instruction' => 'Score the Braden subscales for this critically ill patient.',
        'explanation' => 'Completely limited sensory (1), constantly moist (1), bedfast (1), completely immobile (1), probably inadequate nutrition on tube feeds with residuals (2), friction problem with full lifts (1). Total 7 = very high risk.',
        'scores' => ['sensory' => 1, 'moisture' => 1, 'activity' => 1, 'mobility' => 1, 'nutrition' => 2, 'friction' => 1],
    ],
    [
        'title' => 'Post-op day 1 hip repair',
        'scenario' => 'After right total hip arthroplasty, the patient is chairfast with physical therapy twice daily. She needs two-person assist for pivot transfers and slides slightly on the sheets. Appetite is fair — eats about half of each meal.',
        'instruction' => 'Assign Braden scores reflecting postoperative mobility and nutrition.',
        'explanation' => 'Sensory no impairment (4), moisture occasionally moist (3), activity chairfast (2), mobility very limited (2), nutrition probably inadequate (2), friction potential problem with sliding (2). Total 15 = moderate risk.',
        'scores' => ['sensory' => 4, 'moisture' => 3, 'activity' => 2, 'mobility' => 2, 'nutrition' => 2, 'friction' => 2],
    ],
    [
        'title' => 'Cachectic hospice patient',
        'scenario' => 'A bedbound hospice patient with advanced cancer weighs 92 lb, takes sips of fluid only, and eats less than one-fourth of any meal. He is incontinent of urine and stool and requires total care for repositioning every two hours.',
        'instruction' => 'Rate Braden subscales for end-of-life pressure-injury risk.',
        'explanation' => 'Sensory very limited from fatigue and opioids (2), constantly moist (1), bedfast (1), completely immobile (1), very poor nutrition (1), friction problem with full dependence (1). Total 7 — prioritize comfort and skin protection.',
        'scores' => ['sensory' => 2, 'moisture' => 1, 'activity' => 1, 'mobility' => 1, 'nutrition' => 1, 'friction' => 1],
    ],
    [
        'title' => 'Independent med-surg admission',
        'scenario' => 'A 45-year-old admitted for pneumonia walks the hall independently, eats all meals, has dry intact skin, and repositions without prompting. No sensory deficits reported.',
        'instruction' => 'Score Braden subscales for a low-risk medical patient.',
        'explanation' => 'Sensory no impairment (4), rarely moist (4), walks frequently (4), not limited mobility (4), adequate nutrition (3), no friction problem (3). Total 22 = low risk but continue standard turning and skin inspection.',
        'scores' => ['sensory' => 4, 'moisture' => 4, 'activity' => 4, 'mobility' => 4, 'nutrition' => 3, 'friction' => 3],
    ],
    [
        'title' => 'Stroke with hemiplegia',
        'scenario' => 'Three days after a CVA, the patient has right-sided hemiplegia and aphasia but follows one-step commands. He is chairfast, needs assist for all transfers with noticeable sliding, and has occasional incontinence requiring one extra linen change daily.',
        'instruction' => 'Apply Braden subscales to this stroke survivor.',
        'explanation' => 'Sensory slightly limited on affected side (3), occasionally moist (3), chairfast (2), very limited mobility (2), adequate intake with setup (3), friction potential problem (2). Total 15 = moderate risk — use lift sheet and scheduled turns.',
        'scores' => ['sensory' => 3, 'moisture' => 3, 'activity' => 2, 'mobility' => 2, 'nutrition' => 3, 'friction' => 2],
    ],
    [
        'title' => 'Spinal cord injury new admit',
        'scenario' => 'A patient with a T4 complete spinal cord injury has absent sensation below the nipple line. He is bedfast, cannot turn without maximal assist, and has a indwelling catheter with constant moisture at the sacrum.',
        'instruction' => 'Score pressure-injury risk for immobilized spinal cord injury.',
        'explanation' => 'Completely limited sensory (1), constantly moist at sacrum (1), bedfast (1), completely immobile (1), adequate nutrition on admission (3), friction problem with log-roll transfers (1). Total 8 = very high risk.',
        'scores' => ['sensory' => 1, 'moisture' => 1, 'activity' => 1, 'mobility' => 1, 'nutrition' => 3, 'friction' => 1],
    ],
    [
        'title' => 'Obese patient on bed rest',
        'scenario' => 'A morbidly obese patient is on strict bed rest for heart failure exacerbation. He is alert, eats about 60% of meals, and requires two staff for repositioning with significant shear against the draw sheet.',
        'instruction' => 'Rate Braden factors for an immobile obese patient.',
        'explanation' => 'Sensory no impairment (4), occasionally moist from diaphoresis (3), bedfast (1), completely immobile without assist (1), probably inadequate protein intake (2), friction problem (1). Total 12 = high risk.',
        'scores' => ['sensory' => 4, 'moisture' => 3, 'activity' => 1, 'mobility' => 1, 'nutrition' => 2, 'friction' => 1],
    ],
    [
        'title' => 'Restrained confused elder',
        'scenario' => 'An 88-year-old with delirium has bilateral wrist restraints to prevent line removal. She dozes most of the day, responds only to painful stimuli, is incontinent every shift, and eats less than half of meals.',
        'instruction' => 'Assign Braden scores considering restraints and delirium.',
        'explanation' => 'Very limited sensory from delirium (2), very moist incontinence (2), bedfast with restraints (1), completely immobile (1), probably inadequate nutrition (2), friction problem with repositioning against restraints (1). Total 9 = very high risk — minimize restraint time.',
        'scores' => ['sensory' => 2, 'moisture' => 2, 'activity' => 1, 'mobility' => 1, 'nutrition' => 2, 'friction' => 1],
    ],
    [
        'title' => 'Young trauma on traction',
        'scenario' => 'A 22-year-old in skeletal traction for a femur fracture is alert with full sensation. He is bedfast but shifts upper body position independently. Skin is dry; he eats all meals delivered. Staff use a lift sheet for lower-body repositioning.',
        'instruction' => 'Score Braden subscales for a bedfast but otherwise healthy trauma patient.',
        'explanation' => 'Sensory no impairment (4), rarely moist (4), bedfast due to traction (1), slightly limited — moves upper body (3), excellent appetite (4), potential friction with traction setup (2). Total 18 = at-risk — protect heels and sacrum.',
        'scores' => ['sensory' => 4, 'moisture' => 4, 'activity' => 1, 'mobility' => 3, 'nutrition' => 4, 'friction' => 2],
    ],
];

return nclex_levels(array_map(
    fn (array $bank) => function (int $level, int $index) use ($bank, $bradenOptions): array {
        $subscaleKeys = ['sensory', 'moisture', 'activity', 'mobility', 'nutrition', 'friction'];
        $count = match ($level) {
            1 => 3,
            2, 3 => 4,
            4 => 5,
            5 => 6,
            default => 6,
        };

        $selectedKeys = array_slice($subscaleKeys, 0, $count);
        $subscales = [];

        foreach ($selectedKeys as $key) {
            $subscales[$key] = [
                'label' => $bradenOptions[$key]['label'],
                'options' => $bradenOptions[$key]['options'],
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
