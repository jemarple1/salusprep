<?php

return [
    'scenarios' => [
        [
            'title' => 'Classic FAST positive',
            'scenario' => 'Sudden facial droop, left arm drift, slurred speech — 20 minutes ago.',
            'fast' => ['face' => true, 'arms' => true, 'speech' => true],
            'correct' => 'transport',
            'explanation' => 'Positive FAST within window → stroke center transport and alert.',
        ],
        [
            'title' => 'Arm only',
            'scenario' => 'Patient reports arm numbness after waking, face symmetric, speech clear.',
            'fast' => ['face' => false, 'arms' => true, 'speech' => false],
            'correct' => 'transport',
            'explanation' => 'Focal neuro deficit still warrants stroke evaluation and transport.',
        ],
        [
            'title' => 'Anxiety mimic',
            'scenario' => 'Hyperventilating after argument, symmetric face, equal grip, clear speech.',
            'fast' => ['face' => false, 'arms' => false, 'speech' => false],
            'correct' => 'evaluate',
            'explanation' => 'Negative FAST — evaluate other causes but do not ignore atypical stroke symptoms.',
        ],
        [
            'title' => 'Slurred after fall',
            'scenario' => 'Slurred speech after fall, facial droop, no arm drift.',
            'fast' => ['face' => true, 'arms' => false, 'speech' => true],
            'correct' => 'transport',
            'explanation' => 'Any positive FAST component with acute onset → stroke protocol.',
        ],
        [
            'title' => 'Wake-up deficit',
            'scenario' => 'Found with right weakness at breakfast, last normal at bedtime.',
            'fast' => ['face' => true, 'arms' => true, 'speech' => true],
            'correct' => 'transport',
            'explanation' => 'Document last known well; transport for stroke evaluation.',
        ],
    ],
];
