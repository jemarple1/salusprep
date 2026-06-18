<?php

return [
    'scenarios' => [
        [
            'title' => 'Anaphylaxis with epinephrine pen',
            'drug' => 'Epinephrine (auto-injector)',
            'scenario' => '22-year-old with hives, facial swelling, wheezing, and hypotension after bee sting. Patient carries a prescribed epinephrine auto-injector.',
            'question' => 'Should you assist with the epinephrine auto-injector?',
            'options' => [
                'assist' => 'Yes — assist per anaphylaxis protocol',
                'withhold' => 'No — transport only, no medication',
                'oral_only' => 'No — give oral diphenhydramine only',
            ],
            'correct' => 'assist',
            'explanation' => 'Anaphylaxis with airway compromise and hypotension warrants immediate epinephrine IM per protocol.',
        ],
        [
            'title' => 'Chest pain with hypotension',
            'drug' => 'Nitroglycerin',
            'scenario' => '64-year-old with crushing chest pain. Blood pressure 82/50, pale and diaphoretic. Patient has prescribed nitroglycerin.',
            'question' => 'Should you assist with nitroglycerin?',
            'options' => [
                'assist' => 'Yes — assist with nitroglycerin',
                'withhold' => 'No — withhold nitroglycerin; treat for shock',
                'aspirin_first' => 'Yes — but give nitroglycerin before aspirin',
            ],
            'correct' => 'withhold',
            'explanation' => 'Systolic BP below protocol threshold (typically <100 or <90) is a contraindication to nitroglycerin. Focus on oxygen, IV access, and rapid transport.',
        ],
        [
            'title' => 'Alert hypoglycemia',
            'drug' => 'Oral Glucose',
            'scenario' => '45-year-old diabetic, glucose 54 mg/dL, anxious and tremulous but awake, answering questions appropriately, and able to swallow.',
            'question' => 'Should you assist with oral glucose?',
            'options' => [
                'assist' => 'Yes — assist with oral glucose',
                'withhold' => 'No — IV only, no oral agents',
                'naloxone' => 'No — give naloxone first',
            ],
            'correct' => 'assist',
            'explanation' => 'Conscious patients who can swallow and protect their airway should receive oral glucose for symptomatic hypoglycemia.',
        ],
        [
            'title' => 'Suspected opioid overdose',
            'drug' => 'Naloxone (Narcan)',
            'scenario' => '30-year-old unresponsive. RR 6/min, pinpoint pupils, track marks on arms. SpO₂ 82% on room air.',
            'question' => 'Should you assist with naloxone?',
            'options' => [
                'assist' => 'Yes — assist with naloxone and ventilate',
                'withhold' => 'No — oxygen and transport only',
                'charcoal' => 'No — give activated charcoal',
            ],
            'correct' => 'assist',
            'explanation' => 'Opioid toxidrome with respiratory depression warrants naloxone per protocol along with airway support and oxygen.',
        ],
        [
            'title' => 'Mild allergic reaction',
            'drug' => 'Diphenhydramine',
            'scenario' => '19-year-old with widespread hives after eating shellfish. No airway swelling, no wheezing, BP 118/72, SpO₂ 98%.',
            'question' => 'Should you assist with diphenhydramine per protocol?',
            'options' => [
                'assist' => 'Yes — assist with diphenhydramine per protocol',
                'withhold' => 'No — epinephrine is always required first',
                'ignore' => 'No — no medication indicated for any rash',
            ],
            'correct' => 'assist',
            'explanation' => 'Isolated urticaria without anaphylaxis may be treated with diphenhydramine per protocol while monitoring for progression.',
        ],
    ],
];
