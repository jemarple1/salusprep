<?php

return [
    'scenarios' => array_map(function (array $s) {
        $s['sections'] = ['S' => 'Subjective', 'O' => 'Objective', 'A' => 'Assessment', 'P' => 'Plan'];

        return $s;
    }, [
        [
            'title' => 'Ambulatory MVC patient',
            'scenario' => 'Adult male walking at a bus-vs-car collision, talking in full sentences, scalp laceration with controlled bleeding.',
            'sentences' => [
                ['id' => 's1', 'text' => 'Patient states he was a restrained driver and self-extricated.', 'section' => 'S'],
                ['id' => 's2', 'text' => 'Complains of scalp pain rated 4/10.', 'section' => 'S'],
                ['id' => 's3', 'text' => 'Alert, ambulatory, scalp lac 6 cm with controlled bleeding.', 'section' => 'O'],
                ['id' => 's4', 'text' => 'BP 128/80, pulse 84, RR 16, SpO₂ 99%.', 'section' => 'O'],
                ['id' => 's5', 'text' => 'Minor trauma without life threat.', 'section' => 'A'],
                ['id' => 's6', 'text' => 'Bandage, transport for evaluation.', 'section' => 'P'],
            ],
        ],
        [
            'title' => 'Diabetic confusion',
            'scenario' => '72-year-old female found confused at home, family reports missed lunch and type 2 diabetes history.',
            'sentences' => [
                ['id' => 's1', 'text' => 'Family reports increasing confusion over one hour.', 'section' => 'S'],
                ['id' => 's2', 'text' => 'Known diabetic, no insulin given today.', 'section' => 'S'],
                ['id' => 's3', 'text' => 'Pale, diaphoretic, responds to verbal stimuli only.', 'section' => 'O'],
                ['id' => 's4', 'text' => 'Glucose 48 mg/dL.', 'section' => 'O'],
                ['id' => 's5', 'text' => 'Hypoglycemia with altered mental status.', 'section' => 'A'],
                ['id' => 's6', 'text' => 'Oral glucose, recheck, transport.', 'section' => 'P'],
            ],
        ],
        [
            'title' => 'Asthma exacerbation',
            'scenario' => '19-year-old with wheezing and speaking in short phrases after soccer practice.',
            'sentences' => [
                ['id' => 's1', 'text' => 'Patient reports sudden chest tightness and wheezing.', 'section' => 'S'],
                ['id' => 's2', 'text' => 'Used albuterol inhaler twice without relief.', 'section' => 'S'],
                ['id' => 's3', 'text' => 'Bilateral wheezes, RR 28, accessory muscle use.', 'section' => 'O'],
                ['id' => 's4', 'text' => 'SpO₂ 91% on room air.', 'section' => 'O'],
                ['id' => 's5', 'text' => 'Moderate asthma exacerbation with hypoxia.', 'section' => 'A'],
                ['id' => 's6', 'text' => 'Oxygen, bronchodilator per protocol, transport.', 'section' => 'P'],
            ],
        ],
        [
            'title' => 'Syncope at work',
            'scenario' => '45-year-old fainted briefly in office, now alert and oriented with normal vitals.',
            'sentences' => [
                ['id' => 's1', 'text' => 'Patient reports standing quickly before episode.', 'section' => 'S'],
                ['id' => 's2', 'text' => 'Denies chest pain or palpitations now.', 'section' => 'S'],
                ['id' => 's3', 'text' => 'Alert, skin warm and dry, lungs clear.', 'section' => 'O'],
                ['id' => 's4', 'text' => 'BP 118/72, pulse 76 regular, glucose 102.', 'section' => 'O'],
                ['id' => 's5', 'text' => 'Likely vasovagal syncope, resolved.', 'section' => 'A'],
                ['id' => 's6', 'text' => 'Monitor, transport for evaluation if protocol requires.', 'section' => 'P'],
            ],
        ],
        [
            'title' => 'Abdominal pain',
            'scenario' => '34-year-old with RLQ pain, nausea, low-grade fever for 12 hours.',
            'sentences' => [
                ['id' => 's1', 'text' => 'Pain started periumbilical then moved to RLQ.', 'section' => 'S'],
                ['id' => 's2', 'text' => 'One episode of vomiting, no diarrhea.', 'section' => 'S'],
                ['id' => 's3', 'text' => 'RLQ tenderness with guarding, temp 38.1°C.', 'section' => 'O'],
                ['id' => 's4', 'text' => 'BP 122/78, pulse 96, RR 18.', 'section' => 'O'],
                ['id' => 's5', 'text' => 'Possible acute appendicitis.', 'section' => 'A'],
                ['id' => 's6', 'text' => 'NPO, IV access, transport.', 'section' => 'P'],
            ],
        ],
    ]),
];
