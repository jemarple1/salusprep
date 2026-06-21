<?php

require_once __DIR__.'/helpers.php';

return paramedic_levels([
    [
        'title' => 'Warehouse Collapse — START',
        'scenario' => 'Limited crews. One ambulance available next. Tap the patient who should receive the next transport unit per START triage.',
        'patients' => [
            ['id' => 'a', 'label' => 'Patient A', 'detail' => 'Unconscious, agonal respirations after jaw thrust'],
            ['id' => 'b', 'label' => 'Patient B', 'detail' => 'Alert, open femur fracture, radial pulse present'],
            ['id' => 'c', 'label' => 'Patient C', 'detail' => 'Ambulatory, minor laceration, tagged green'],
            ['id' => 'd', 'label' => 'Patient D', 'detail' => 'Apneic after positioning, no pulse — black tag applied'],
        ],
        'correct' => 'a',
        'explanation' => 'NHTSA/CDC MCI triage (START): immediate (red) patients have salvageable life threats — agonal respirations after positioning may respond to airway intervention. Black tag patient excluded; green ambulatory waits; yellow stable fracture lower than immediate airway threat.',
    ],
    [
        'title' => 'Highway Pileup — Resource Allocation',
        'scenario' => 'Two ambulances on scene, third 20 minutes out. Who gets the next unit?',
        'patients' => [
            ['id' => 'entrapped', 'label' => 'Patient 1', 'detail' => 'Entrapped, alert, pelvis pain, vitals stable — extrication in progress'],
            ['id' => 'resp', 'label' => 'Patient 2', 'detail' => 'RR 32, SpO₂ 86%, confused after MVC, accessible now'],
            ['id' => 'walking', 'label' => 'Patient 3', 'detail' => 'Three walking wounded with minor injuries'],
            ['id' => 'dead', 'label' => 'Patient 4', 'detail' => 'Obvious incompatible with life injuries'],
        ],
        'correct' => 'resp',
        'explanation' => 'CDC field triage and NHTSA MCI principles prioritize patients with immediate life threats who benefit from rapid transport — hypoxic altered patient outranks stable entrapped and minor injuries when one unit is available.',
    ],
    [
        'title' => 'Building Fire — SALT',
        'scenario' => 'SALT triage in progress. Select highest priority for immediate lifesaving intervention and transport.',
        'patients' => [
            ['id' => 'smoke', 'label' => 'Patient A', 'detail' => 'Confused, soot around nose, RR 28, SpO₂ 88%'],
            ['id' => 'burn', 'label' => 'Patient B', 'detail' => 'Partial-thickness burns 12% TBSA, alert, stable vitals'],
            ['id' => 'ankle', 'label' => 'Patient C', 'detail' => 'Ankle sprain, ambulatory'],
            ['id' => 'chest', 'label' => 'Patient D', 'detail' => 'Crush injury, BP 82/50, pale, trapped 5 more minutes'],
        ],
        'correct' => 'smoke',
        'explanation' => 'CDC SALT triage sorts by lifesaving intervention need — smoke inhalation with hypoxia and altered mental status requires immediate oxygen and transport. Crush may need resources but extrication delay; smoke patient accessible now with immediate threat.',
    ],
    [
        'title' => 'School Bus — JumpSTART Pediatric',
        'scenario' => 'Pediatric MCI. JumpSTART applied. One pediatric unit available — choose next transport.',
        'patients' => [
            ['id' => 'infant_resp', 'label' => 'Patient A', 'detail' => '2-year-old, RR 8/min after positioning, pulses present'],
            ['id' => 'school_age', 'label' => 'Patient B', 'detail' => '8-year-old, alert, forearm deformity, controlled bleeding'],
            ['id' => 'teen_walk', 'label' => 'Patient C', 'detail' => '15-year-old, walking, glass in scalp, minor'],
            ['id' => 'pulseless_child', 'label' => 'Patient D', 'detail' => '5-year-old, apneic, no pulse after 5 rescue breaths — expectant'],
        ],
        'correct' => 'infant_resp',
        'explanation' => 'CDC JumpSTART pediatric MCI algorithm: child with inadequate respirations after airway positioning but pulses present is immediate priority for ventilatory support and transport — differs from adult START expectant category for apneic pulseless after breaths.',
    ],
    [
        'title' => 'Train Derailment — Triage Officer',
        'scenario' => 'Incident command requests next RED patient for sole remaining MICU.',
        'patients' => [
            ['id' => 'amputation', 'label' => 'Patient A', 'detail' => 'Partial amputation, tourniquet applied, alert, BP 118/76'],
            ['id' => 'airway', 'label' => 'Patient B', 'detail' => 'Gurgling respirations, GCS 10, facial trauma'],
            ['id' => 'geriatric', 'label' => 'Patient C', 'detail' => 'Hip pain, ambulatory with assistance'],
            ['id' => 'psych', 'label' => 'Patient D', 'detail' => 'Panic attack, vitals stable, no injuries'],
        ],
        'correct' => 'airway',
        'explanation' => 'NHTSA EMS MCI command and CDC triage: airway compromise with altered LOC is highest acuity among accessible patients — controlled amputation is urgent but stable after tourniquet; MICU resources match airway/ventilation need.',
    ],
    [
        'title' => 'Storm Shelter Overflow',
        'scenario' => 'One ALS unit left after tornado. Select patient for transport now.',
        'patients' => [
            ['id' => 'pregnant', 'label' => 'Patient A', 'detail' => '36 weeks pregnant, contractions q3 min, crowning not yet'],
            ['id' => 'asthma', 'label' => 'Patient B', 'detail' => 'RR 34, SpO₂ 87%, speaking single words, no nebulizer left on scene'],
            ['id' => 'laceration', 'label' => 'Patient C', 'detail' => 'Hand laceration, bleeding controlled, alert'],
            ['id' => 'anxiety', 'label' => 'Patient D', 'detail' => 'Hyperventilating, SpO₂ 99%, no trauma'],
        ],
        'correct' => 'asthma',
        'explanation' => 'CDC MCI resource allocation: treat greatest good — hypoxic respiratory failure threatens life within minutes; obstetric patient needs transport but may have more time; controlled laceration and anxiety lower priority (NHTSA triage officer training).',
    ],
    [
        'title' => 'Hazmat MCI — Contamination',
        'scenario' => 'Chemical release. Decon lane established. Next transport from hot zone after decon:',
        'patients' => [
            ['id' => 'bronchospasm', 'label' => 'Patient A', 'detail' => 'Wheezing, RR 30, SpO₂ 89% after exposure, decontaminated'],
            ['id' => 'rash', 'label' => 'Patient B', 'detail' => 'Skin erythema only, vitals stable'],
            ['id' => 'broken_leg', 'label' => 'Patient C', 'detail' => 'Closed tibia fracture from fleeing crowd'],
            ['id' => 'asymptomatic', 'label' => 'Patient D', 'detail' => 'No symptoms, minimal exposure, decontaminated'],
        ],
        'correct' => 'bronchospasm',
        'explanation' => 'CDC emergency preparedness hazmat/MCI guidance: after decontamination, prioritize airway and breathing casualties from chemical bronchospasm — delayed transport worsens pulmonary injury; stable dermatologic and orthopedic injuries triaged lower.',
    ],
    [
        'title' => 'Active Scene — Tactical Triage',
        'scenario' => 'Warm zone operations. Two patients reachable simultaneously — one transport slot.',
        'patients' => [
            ['id' => 'hemorrhage', 'label' => 'Patient A', 'detail' => 'Junctional hemorrhage controlled with hemostatic dressing, weak pulses, altered'],
            ['id' => 'gsw_chest', 'label' => 'Patient B', 'detail' => 'GSW chest, absent breath sounds left, BP 70/40, accessible'],
            ['id' => 'minor_shrapnel', 'label' => 'Patient C', 'detail' => 'Shrapnel to arm, walking'],
            ['id' => 'dead', 'label' => 'Patient D', 'detail' => 'Incompatible with life — no interventions'],
        ],
        'correct' => 'gsw_chest',
        'explanation' => 'NHTSA tactical EMS and CDC hemorrhage control: tension physiology or massive chest trauma with shock may need immediate needle decompression and rapid transport — junctional hemorrhage controlled but both are red; chest with absent breath sounds and hypotension is immediately life-threatening.',
    ],
    [
        'title' => 'Nursing Home Evacuation MCI',
        'scenario' => 'Facility fire evacuation. Limited wheelchairs and one stretcher van.',
        'patients' => [
            ['id' => 'copd', 'label' => 'Patient A', 'detail' => 'COPD on 2 L NC normally, now SpO₂ 84%, RR 28, confused'],
            ['id' => 'dementia_walk', 'label' => 'Patient B', 'detail' => 'Dementia, walking, minor smoke smell on clothes'],
            ['id' => 'bedbound', 'label' => 'Patient C', 'detail' => 'Bedbound, chronic conditions, vitals stable, no smoke exposure symptoms'],
            ['id' => 'fracture', 'label' => 'Patient D', 'detail' => 'Fall during evac, hip pain, vitals stable'],
        ],
        'correct' => 'copd',
        'explanation' => 'CDC older adult emergency planning: hypoxemic confused COPD patient during smoke exposure needs immediate oxygen and transport — vulnerable population at higher risk; stable chronic patients and minor injuries lower priority in resource-limited evacuation.',
    ],
    [
        'title' => 'Mass Shooting — SALT Sort',
        'scenario' => 'SALT sort: "Who needs lifesaving intervention now?" One paramedic crew.',
        'patients' => [
            ['id' => 'exsanguinating', 'label' => 'Patient A', 'detail' => 'Femoral hemorrhage, tourniquet applied 2 min ago, still oozing, BP 90/60, alert'],
            ['id' => 'penetrating_abd', 'label' => 'Patient B', 'detail' => 'Abdominal GSW, rigid abdomen, BP 74/40, pale'],
            ['id' => 'flesh_wound', 'label' => 'Patient C', 'detail' => 'Through-and-through soft tissue arm, stable'],
            ['id' => 'hide', 'label' => 'Patient D', 'detail' => 'Hiding under desk, no visible injury, panic'],
        ],
        'correct' => 'penetrating_abd',
        'explanation' => 'CDC Stop the Bleed and SALT triage: uncontrolled internal hemorrhage with shock (rigid abdomen, MAP-critical hypotension) exceeds controlled extremity bleeding with tourniquet — both red but abdominal shock without surgical control is highest mortality without immediate transport (NHTSA MCI triage).',
    ],
]);
