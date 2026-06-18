<?php

return [
    'scenarios' => [
        [
            'title' => 'Warehouse collapse',
            'scenario' => 'Limited crews on scene. Tap the patient who should get the next ambulance.',
            'patients' => [
                ['id' => 'a', 'label' => 'Patient A', 'detail' => 'Unconscious, agonal respirations'],
                ['id' => 'b', 'label' => 'Patient B', 'detail' => 'Alert, open femur fracture'],
                ['id' => 'c', 'label' => 'Patient C', 'detail' => 'Walking, arm laceration'],
                ['id' => 'd', 'label' => 'Patient D', 'detail' => 'Apneic after airway maneuver, weak pulse'],
            ],
            'correct' => 'd',
            'explanation' => 'Patient D may benefit from immediate airway/perfusion intervention.',
        ],
        [
            'title' => 'Highway pileup',
            'scenario' => 'Two ambulances available. Who goes first?',
            'patients' => [
                ['id' => 'agonal', 'label' => 'Patient 1', 'detail' => 'Unresponsive, agonal respirations'],
                ['id' => 'entrapped', 'label' => 'Patient 2', 'detail' => 'Entrapped, alert, pelvis pain'],
                ['id' => 'walking', 'label' => 'Patient 3', 'detail' => 'Walking wounded ×3'],
                ['id' => 'pulseless', 'label' => 'Patient 4', 'detail' => 'Apneic, no pulse after positioning'],
            ],
            'correct' => 'agonal',
            'explanation' => 'Potentially salvageable airway/respiratory emergency before stable entrapped or minor patients.',
        ],
        [
            'title' => 'Building fire evac',
            'scenario' => 'Select highest priority for transport.',
            'patients' => [
                ['id' => 'smoke', 'label' => 'Patient A', 'detail' => 'Confused, soot around nose, RR 28'],
                ['id' => 'burn', 'label' => 'Patient B', 'detail' => 'Partial-thickness burns 9% TBSA, alert'],
                ['id' => 'ankle', 'label' => 'Patient C', 'detail' => 'Ankle sprain, ambulatory'],
                ['id' => 'chest', 'label' => 'Patient D', 'detail' => 'Crush injury, BP 84/50, pale'],
            ],
            'correct' => 'chest',
            'explanation' => 'Hypotensive crush injury is highest acuity among these options.',
        ],
        [
            'title' => 'Storm shelter overflow',
            'scenario' => 'One unit left. Choose now.',
            'patients' => [
                ['id' => 'pregnant', 'label' => 'Patient A', 'detail' => '36 weeks pregnant, contractions q3 min'],
                ['id' => 'laceration', 'label' => 'Patient B', 'detail' => 'Hand laceration, controlled bleeding'],
                ['id' => 'asthma', 'label' => 'Patient C', 'detail' => 'RR 32, SpO₂ 88%, speaking words'],
                ['id' => 'anxiety', 'label' => 'Patient D', 'detail' => 'Hyperventilating, vitals stable'],
            ],
            'correct' => 'asthma',
            'explanation' => 'Hypoxic respiratory distress outranks stable laceration or anxiety.',
        ],
        [
            'title' => 'Train derailment',
            'scenario' => 'Triage officer — next transport?',
            'patients' => [
                ['id' => 'amputation', 'label' => 'Patient A', 'detail' => 'Partial amputation, tourniquet applied, alert'],
                ['id' => 'dead', 'label' => 'Patient B', 'detail' => 'Obvious death'],
                ['id' => 'geriatric', 'label' => 'Patient C', 'detail' => 'Hip pain, ambulatory with help'],
                ['id' => 'airway', 'label' => 'Patient D', 'detail' => 'Gurgling respirations, altered'],
            ],
            'correct' => 'airway',
            'explanation' => 'Airway compromise with altered status is immediate priority.',
        ],
    ],
];
