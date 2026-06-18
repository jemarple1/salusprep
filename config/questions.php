<?php

return [

    'recalibration' => [
        'min_attempts' => (int) env('QUESTION_RECAL_MIN_ATTEMPTS', 30),
        'bands' => [
            1 => 85,
            2 => 70,
            3 => 50,
            4 => 35,
            5 => 0,
        ],
    ],

];
