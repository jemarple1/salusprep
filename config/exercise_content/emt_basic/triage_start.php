<?php

$mk = fn (string $title, string $scenario, string $correct, string $explanation) => compact('title', 'scenario', 'correct', 'explanation');

return [
    'scenarios' => [
        $mk('Ambulatory scalp lac', 'Adult walking and talking with controlled scalp bleeding at an MVC.', 'minor', 'Ambulatory patients are tagged Green (Minor) in START.'),
        $mk('Apneic pulseless', 'Adult trapped, apneic after airway positioning, no radial pulse.', 'expectant', 'Apneic without perfusion after positioning is Expectant (Black) in START when resources are limited.'),
        $mk('Respirations 32', 'Non-ambulatory adult, RR 32/min, follows commands, strong radial pulse.', 'immediate', 'Respirations over 30/min place the patient in Immediate (Red) category.'),
        $mk('Unable to follow commands', 'Non-ambulatory, RR 20, cannot obey commands, pulse present.', 'delayed', 'Breathing under 30 with perfusion but unable to obey commands → Delayed (Yellow).'),
        $mk('Walking with femur deformity', 'Patient ambulatory with obvious femur deformity and pain.', 'minor', 'If the patient can walk to you, START assigns Green first regardless of significant extremity injury.'),
    ],
];
