<?php

return [
    'scenarios' => [
        [
            'title' => 'Pediatric wheezing',
            'scenario' => '18-month-old with retractions and wheezes.',
            'vitals' => ['bp' => '—', 'pulse' => '148', 'rr' => '40', 'spo2' => '89%', 'temp' => '38.2°C', 'glucose' => '—'],
            'correct' => 'oxygen',
            'explanation' => 'Hypoxia and respiratory distress → oxygen and rapid transport.',
        ],
        [
            'title' => 'Hypotensive chest pain',
            'scenario' => '55-year-old diaphoretic with chest pressure.',
            'vitals' => ['bp' => '86/58', 'pulse' => '118', 'rr' => '22', 'spo2' => '92%', 'temp' => '98.6°F', 'glucose' => '110'],
            'correct' => 'oxygen_iv',
            'explanation' => 'Hypotension + chest pain → oxygen, IV, ECG, rapid transport.',
        ],
        [
            'title' => 'Stable ankle sprain',
            'scenario' => '22-year-old rolled ankle, pain with weight bearing.',
            'vitals' => ['bp' => '122/76', 'pulse' => '78', 'rr' => '14', 'spo2' => '99%', 'temp' => '98.4°F', 'glucose' => '—'],
            'correct' => 'comfort_transport',
            'explanation' => 'Stable vitals — ice, immobilize, transport per patient preference/protocol.',
        ],
        [
            'title' => 'Sepsis suspicion',
            'scenario' => 'Elderly patient with fever and altered mental status.',
            'vitals' => ['bp' => '92/54', 'pulse' => '124', 'rr' => '26', 'spo2' => '94%', 'temp' => '39.4°C', 'glucose' => '186'],
            'correct' => 'oxygen_iv',
            'explanation' => 'Fever, hypotension, tachycardia, AMS → high-risk sepsis pathway.',
        ],
        [
            'title' => 'Hyperglycemia',
            'scenario' => 'Known diabetic with polyuria and weakness.',
            'vitals' => ['bp' => '134/82', 'pulse' => '102', 'rr' => '24', 'spo2' => '98%', 'temp' => '98.8°F', 'glucose' => '412'],
            'correct' => 'transport_iv',
            'explanation' => 'Significant hyperglycemia with symptoms → transport, IV, monitor.',
        ],
    ],
];
