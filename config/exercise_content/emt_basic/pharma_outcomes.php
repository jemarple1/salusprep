<?php

return [
    'scenarios' => [
        [
            'title' => 'After albuterol',
            'drug' => 'Albuterol',
            'scenario' => 'You assisted a wheezing patient with 2 puffs of their prescribed albuterol MDI. Re-assessment in 5 minutes.',
            'question' => 'Which finding best indicates the medication is working?',
            'options' => [
                'wheezing_decreased' => 'Wheezing decreased and SpO₂ improved',
                'bp_drop' => 'Blood pressure dropped to 70/40',
                'pupils_pinpoint' => 'Pupils became pinpoint',
                'glucose_low' => 'Glucose fell to 40 mg/dL',
            ],
            'correct' => 'wheezing_decreased',
            'explanation' => 'Effective bronchodilation should reduce wheezing and improve oxygenation.',
        ],
        [
            'title' => 'After nitroglycerin',
            'drug' => 'Nitroglycerin',
            'scenario' => 'Chest pain patient received nitroglycerin 0.4 mg SL per protocol. BP remains above 100 systolic.',
            'question' => 'What outcome suggests the nitroglycerin helped?',
            'options' => [
                'pain_improved' => 'Chest pain intensity decreased',
                'pain_worse' => 'Chest pain became crushing and radiates to both arms with new ST elevation on monitor',
                'anaphylaxis' => 'Developed hives and wheezing',
                'hypoglycemia' => 'Became confused with glucose 55',
            ],
            'correct' => 'pain_improved',
            'explanation' => 'Nitroglycerin vasodilation often reduces myocardial ischemic chest pain when appropriately indicated.',
        ],
        [
            'title' => 'After oral glucose',
            'drug' => 'Oral Glucose',
            'scenario' => 'Hypoglycemic patient received oral glucose gel. Repeat glucose in 10 minutes.',
            'question' => 'What would indicate improvement?',
            'options' => [
                'ams_resolved' => 'Mental status returned to normal and glucose rose',
                'resp_depression' => 'Respiratory rate fell to 6/min',
                'hives' => 'Generalized hives appeared',
                'chest_pain' => 'Crushing chest pain developed',
            ],
            'correct' => 'ams_resolved',
            'explanation' => 'Successful hypoglycemia treatment restores mental status and raises blood glucose.',
        ],
        [
            'title' => 'After naloxone',
            'drug' => 'Naloxone (Narcan)',
            'scenario' => 'Opioid overdose patient received naloxone IN. You are monitoring breathing and mental status.',
            'question' => 'Which change suggests naloxone is effective?',
            'options' => [
                'rr_increased' => 'Respiratory rate and mental status improved',
                'spo2_worse' => 'SpO₂ dropped to 70% without other cause',
                'anaphylaxis' => 'Facial swelling and stridor developed',
                'hypotension' => 'BP fell to 80/50 after nitroglycerin',
            ],
            'correct' => 'rr_increased',
            'explanation' => 'Naloxone reverses opioid-induced respiratory depression — expect improved RR and alertness.',
        ],
        [
            'title' => 'After oxygen',
            'drug' => 'Oxygen',
            'scenario' => 'Patient with SpO₂ 88% placed on high-flow oxygen via non-rebreather mask.',
            'question' => 'What finding shows oxygen is improving the patient?',
            'options' => [
                'spo2_up' => 'SpO₂ increased to 94% or higher',
                'glucose_up' => 'Blood glucose increased to 200',
                'pain_gone' => 'All chest pain resolved without other treatment',
                'pupils_dilated' => 'Pupils became fully dilated and fixed',
            ],
            'correct' => 'spo2_up',
            'explanation' => 'Supplemental oxygen should raise SpO₂ in hypoxic patients when the airway and delivery device are appropriate.',
        ],
    ],
];
