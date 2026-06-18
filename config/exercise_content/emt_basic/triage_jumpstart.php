<?php

$mk = fn (string $title, string $scenario, string $correct, string $explanation) => compact('title', 'scenario', 'correct', 'explanation');

return [
    'scenarios' => [
        $mk('Walking child', '9-year-old ambulatory, crying, open forearm fracture, RR 24.', 'minor', 'Ambulatory pediatric patients → Green (Minor) in JumpSTART.'),
        $mk('Apneic no pulse', '6-year-old apneic after positioning, no peripheral pulses.', 'expectant', 'Apneic without pulses after positioning → Expectant unless mass resuscitation is available.'),
        $mk('Apneic with pulse', '4-year-old apneic after jaw thrust, slow radial pulse present.', 'immediate', 'Apneic with perfusion → Immediate (Red), ventilate and reassess.'),
        $mk('RR over 45', 'Non-ambulatory child, RR 50/min, responds to painful stimulus.', 'immediate', 'Respiratory rate over 45/min → Immediate (Red) in JumpSTART.'),
        $mk('Delayed pediatric', 'Non-ambulatory 8-year-old, RR 22, obeys commands, cap refill 2 sec.', 'delayed', 'Breathing under 45, perfusion present, obeys commands → Delayed (Yellow).'),
    ],
];
