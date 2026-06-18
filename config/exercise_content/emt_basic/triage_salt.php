<?php

$mk = fn (string $title, string $scenario, string $correct, string $explanation) => compact('title', 'scenario', 'correct', 'explanation');

return [
    'scenarios' => [
        $mk('Warm zone hemorrhage', 'Conscious patient, tourniquet on thigh placed 1 minute ago, talking.', 'lifesaving', 'SALT: lifesaving interventions already started → urgent treatment/transport.'),
        $mk('Ambulatory exit', 'Alert patient walking from venue, rib pain, no major bleeding.', 'minor', 'Ambulatory without immediate life threat → Minor category in SALT sort.'),
        $mk('Active threat hold', 'Patient in warm zone, no immediate life threat identified yet.', 'assess', 'SALT begins with Sort/Assess before movement when threat persists.'),
        $mk('Airway obstruction', 'Patient choking, ineffective cough, cyanosis.', 'lifesaving', 'Airway obstruction requires immediate lifesaving intervention in SALT.'),
        $mk('Obvious death', 'Decapitation in cold zone triage area.', 'expectant', 'Incompatible with life → Expectant/deceased category.'),
    ],
];
