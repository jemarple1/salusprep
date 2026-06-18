<?php

return [
    'scenarios' => [
        [
            'title' => 'Symptomatic hypoglycemia',
            'scenario' => 'Shaking, diaphoresis, confusion. Fingerstick glucose 51 mg/dL. Patient is awake and can swallow.',
            'question' => 'Which EMT-Basic medication is most appropriate?',
            'options' => [
                'oral_glucose' => 'Oral glucose',
                'naloxone' => 'Naloxone (Narcan)',
                'nitroglycerin' => 'Nitroglycerin',
                'albuterol' => 'Albuterol',
            ],
            'correct' => 'oral_glucose',
            'explanation' => 'Altered mental status with documented hypoglycemia → oral glucose when the patient can swallow safely.',
        ],
        [
            'title' => 'Bronchospasm',
            'scenario' => 'Audible wheezes, prolonged expiratory phase, SpO₂ 91%, history of asthma. Patient has a prescribed MDI.',
            'question' => 'Which medication should you assist with?',
            'options' => [
                'albuterol' => 'Albuterol (MDI)',
                'epinephrine' => 'Epinephrine auto-injector',
                'aspirin' => 'Aspirin',
                'diphenhydramine' => 'Diphenhydramine',
            ],
            'correct' => 'albuterol',
            'explanation' => 'Bronchospasm with wheezing is treated with a bronchodilator — assist with albuterol per protocol.',
        ],
        [
            'title' => 'Suspected ACS chest pain',
            'scenario' => '55-year-old with substernal pressure radiating to the jaw. No nitroglycerin allergy. BP 132/84.',
            'question' => 'Which medication is typically given first for suspected acute coronary syndrome?',
            'options' => [
                'aspirin' => 'Aspirin',
                'naloxone' => 'Naloxone',
                'oral_glucose' => 'Oral glucose',
                'activated_charcoal' => 'Activated charcoal',
            ],
            'correct' => 'aspirin',
            'explanation' => 'Aspirin is indicated early for suspected MI when no allergy or active bleeding is present.',
        ],
        [
            'title' => 'Opioid toxidrome',
            'scenario' => 'Unresponsive male. Pinpoint pupils, respiratory rate 8/min, cyanosis around lips.',
            'question' => 'Which medication matches this presentation?',
            'options' => [
                'naloxone' => 'Naloxone (Narcan)',
                'nitroglycerin' => 'Nitroglycerin',
                'diphenhydramine' => 'Diphenhydramine',
                'oral_glucose' => 'Oral glucose',
            ],
            'correct' => 'naloxone',
            'explanation' => 'Pinpoint pupils + respiratory depression suggests opioid overdose → naloxone per protocol.',
        ],
        [
            'title' => 'Hypoxia',
            'scenario' => 'SpO₂ 88% on room air after trauma. Increased work of breathing. No supplemental oxygen applied yet.',
            'question' => 'Which EMT-Basic intervention is most appropriate?',
            'options' => [
                'oxygen' => 'Oxygen',
                'oral_glucose' => 'Oral glucose',
                'activated_charcoal' => 'Activated charcoal',
                'aspirin' => 'Aspirin',
            ],
            'correct' => 'oxygen',
            'explanation' => 'SpO₂ below protocol threshold requires supplemental oxygen.',
        ],
    ],
];
