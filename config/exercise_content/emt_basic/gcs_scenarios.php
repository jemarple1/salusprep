<?php

return [
    'scenarios' => [
        ['title' => 'Fall — confused', 'scenario' => 'Opens eyes to voice, confused conversation, localizes pain.', 'eye' => 3, 'verbal' => 4, 'motor' => 5, 'explanation' => 'E3 V4 M5 = GCS 12.'],
        ['title' => 'MVC unresponsive', 'scenario' => 'Opens eyes to pain only, incomprehensible sounds, decerebrate posturing.', 'eye' => 2, 'verbal' => 2, 'motor' => 2, 'explanation' => 'E2 V2 M2 = GCS 6 — severe injury.'],
        ['title' => 'Intoxicated', 'scenario' => 'Eyes open spontaneously, oriented but slurred, obeys commands.', 'eye' => 4, 'verbal' => 5, 'motor' => 6, 'explanation' => 'E4 V5 M6 = GCS 15 despite intoxication — document carefully.'],
        ['title' => 'Head strike', 'scenario' => 'Eyes to speech, inappropriate words, withdraws from pain.', 'eye' => 3, 'verbal' => 3, 'motor' => 4, 'explanation' => 'E3 V3 M4 = GCS 10.'],
        ['title' => 'Postictal', 'scenario' => 'Eyes open spontaneously, confused, localizes pain.', 'eye' => 4, 'verbal' => 4, 'motor' => 5, 'explanation' => 'E4 V4 M5 = GCS 13 postictal state.'],
    ],
];
