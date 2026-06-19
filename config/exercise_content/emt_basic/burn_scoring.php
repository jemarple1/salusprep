<?php

return [
    'scenarios' => [
        [
            'title' => 'Scald chest and arm',
            'scenario' => 'Hot liquid spilled on anterior chest and entire right arm. Partial-thickness with blisters.',
            'correct_regions' => ['anterior:chest', 'anterior:arm_r'],
            'correct_percent' => '27',
            'percent_options' => ['9' => '9% TBSA', '18' => '18% TBSA', '27' => '27% TBSA', '36' => '36% TBSA'],
            'explanation' => 'Anterior trunk 18% + entire arm 9% ≈ 27% TBSA.',
        ],
        [
            'title' => 'Grease fire legs',
            'scenario' => 'Burns to both anterior legs from knees to ankles.',
            'correct_regions' => ['anterior:leg_l', 'anterior:leg_r'],
            'correct_percent' => '18',
            'percent_options' => ['9' => '9% TBSA', '18' => '18% TBSA', '27' => '27% TBSA', '36' => '36% TBSA'],
            'explanation' => 'Each lower leg ≈ 9% TBSA → about 18% total.',
        ],
        [
            'title' => 'Campfire hand',
            'scenario' => 'Partial-thickness burn to entire left hand and forearm.',
            'correct_regions' => ['anterior:arm_l'],
            'correct_percent' => '9',
            'percent_options' => ['4' => '4.5% TBSA', '9' => '9% TBSA', '18' => '18% TBSA', '27' => '27% TBSA'],
            'explanation' => 'Entire arm (including hand) ≈ 9% TBSA by rule of nines.',
        ],
        [
            'title' => 'Flash burn face and chest',
            'scenario' => 'Flash burn to face and front of chest only.',
            'correct_regions' => ['anterior:head', 'anterior:chest'],
            'correct_percent' => '27',
            'percent_options' => ['9' => '9% TBSA', '18' => '18% TBSA', '27' => '27% TBSA', '36' => '36% TBSA'],
            'explanation' => 'Head 9% + anterior chest 18% ≈ 27% TBSA.',
        ],
        [
            'title' => 'House fire back',
            'scenario' => 'Patient burned on entire back after clothing ignited. Front uninjured.',
            'correct_regions' => ['posterior:back'],
            'correct_percent' => '18',
            'percent_options' => ['9' => '9% TBSA', '18' => '18% TBSA', '27' => '27% TBSA', '36' => '36% TBSA'],
            'explanation' => 'Entire back ≈ 18% TBSA.',
        ],
    ],
];
