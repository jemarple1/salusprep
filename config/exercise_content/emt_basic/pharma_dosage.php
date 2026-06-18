<?php

return [
    'scenarios' => [
        [
            'title' => 'Epinephrine auto-injector — adult',
            'drug' => 'Epinephrine (auto-injector)',
            'scenario' => 'Adult patient in anaphylaxis. You are assisting with a prescribed epinephrine auto-injector.',
            'question' => 'What is the standard adult dose via auto-injector?',
            'options' => [
                '0.3mg' => '0.3 mg IM',
                '0.15mg' => '0.15 mg IM',
                '1mg' => '1 mg IV push',
                '324mg' => '324 mg oral',
            ],
            'correct' => '0.3mg',
            'explanation' => 'Adult epinephrine auto-injector delivers 0.3 mg IM (typically 0.3 mg/0.3 mL). Pediatric auto-injectors deliver 0.15 mg.',
        ],
        [
            'title' => 'Albuterol MDI',
            'drug' => 'Albuterol',
            'scenario' => 'Patient with bronchospasm has a prescribed metered-dose inhaler and spacer.',
            'question' => 'How many puffs are typically assisted per albuterol MDI dose?',
            'options' => [
                '2_puffs' => '2 puffs',
                '10_puffs' => '10 puffs at once',
                '1_puff' => '1 puff only, never repeat',
                '0.4mg' => '0.4 mg sublingual',
            ],
            'correct' => '2_puffs',
            'explanation' => 'Standard MDI assist is 2 puffs, reassess, and repeat per protocol if wheezing persists.',
        ],
        [
            'title' => 'Nitroglycerin SL',
            'drug' => 'Nitroglycerin',
            'scenario' => 'Chest pain patient meets criteria for nitroglycerin. BP adequate. One dose already given — pain persists after 5 minutes.',
            'question' => 'What is the dose per nitroglycerin tablet/spray administration?',
            'options' => [
                '0.4mg' => '0.4 mg sublingual',
                '0.3mg' => '0.3 mg IM',
                '2mg' => '2 mg intranasal',
                '324mg' => '324 mg oral',
            ],
            'correct' => '0.4mg',
            'explanation' => 'Nitroglycerin is given 0.4 mg SL (tablet or spray) per dose, with protocol limits on total doses and reassessment between doses.',
        ],
        [
            'title' => 'Aspirin — ACS',
            'drug' => 'Aspirin',
            'scenario' => 'Suspected acute coronary syndrome. No aspirin allergy or active bleeding.',
            'question' => 'What is the standard aspirin dose to assist with?',
            'options' => [
                '324mg' => '324 mg chewable',
                '81mg' => '81 mg chewable',
                '650mg' => '650 mg swallowed whole only',
                '0.4mg' => '0.4 mg sublingual',
            ],
            'correct' => '324mg',
            'explanation' => 'EMT-Basic protocol typically uses 324 mg chewable aspirin (four 81 mg tablets or equivalent) for suspected MI.',
        ],
        [
            'title' => 'Oral glucose gel',
            'drug' => 'Oral Glucose',
            'scenario' => 'Symptomatic hypoglycemia in an alert patient who can swallow.',
            'question' => 'What is a typical oral glucose dose?',
            'options' => [
                '15g' => '15 g oral glucose gel/tablets',
                '324mg' => '324 mg chewable',
                '0.4mg' => '0.4 mg sublingual',
                '50g' => '50 g activated charcoal',
            ],
            'correct' => '15g',
            'explanation' => 'Oral glucose is typically given as 15 g, reassess, and repeat per protocol until mental status and glucose improve.',
        ],
    ],
];
