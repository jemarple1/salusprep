<?php

require_once __DIR__.'/helpers.php';

return paramedic_levels([
    [
        'title' => 'Classic FAST Positive — Stroke Alert',
        'scenario' => '64-year-old sudden facial droop, left arm drift, slurred speech onset 25 minutes ago. Last known well confirmed.',
        'fast' => ['face' => true, 'arms' => true, 'speech' => true],
        'correct' => 'transport',
        'explanation' => 'NIH Stroke Scale and CDC stroke guidance: positive FAST within treatment window requires rapid transport to stroke-capable center with pre-arrival notification — time is brain.',
    ],
    [
        'title' => 'Arm Weakness Only',
        'scenario' => '55-year-old woke with right arm numbness, face symmetric, speech clear, onset 45 minutes ago.',
        'fast' => ['face' => false, 'arms' => true, 'speech' => false],
        'correct' => 'transport',
        'explanation' => 'CDC notes stroke can present with isolated focal deficits — any acute neurologic deficit warrants stroke evaluation and transport; do not require full FAST positivity (NIH stroke awareness materials).',
    ],
    [
        'title' => 'Anxiety Mimic — Negative FAST',
        'scenario' => 'Hyperventilating after argument, tingling fingers, symmetric smile, equal grip strength, clear speech, no focal deficit on exam.',
        'fast' => ['face' => false, 'arms' => false, 'speech' => false],
        'correct' => 'evaluate',
        'explanation' => 'Negative FAST with clear alternative explanation still requires thorough evaluation — CDC cautions atypical stroke symptoms (vertigo, vision loss) may occur without classic FAST, but pure anxiety findings without focal deficit may be managed per protocol with transport if uncertainty remains.',
    ],
    [
        'title' => 'LVO Suspicion — Cortical Signs',
        'scenario' => 'Sudden right hemiplegia, left gaze preference, global aphasia, onset 40 minutes ago. FAST positive. Consider large vessel occlusion.',
        'fast' => ['face' => true, 'arms' => true, 'speech' => true],
        'correct' => 'transport',
        'explanation' => 'NIH stroke research identifies cortical signs (gaze deviation, aphasia, dense hemiparesis) suggesting LVO — transport to comprehensive stroke center capable of thrombectomy when within window (CDC stroke systems of care).',
    ],
    [
        'title' => 'Wake-Up Stroke',
        'scenario' => 'Found at 0700 with facial droop and confusion. Last seen normal at 2300. Vitals stable.',
        'fast' => ['face' => true, 'arms' => false, 'speech' => true],
        'correct' => 'transport',
        'explanation' => 'CDC stroke education: wake-up strokes require transport for advanced imaging — last known well time guides eligibility; prehospital notification essential even when exact onset unknown (NIH stroke timeline guidance).',
    ],
    [
        'title' => 'Slurred Speech Post Fall',
        'scenario' => 'Elderly fell, now slurred speech and facial asymmetry. Uncertain if deficits preceded fall. GCS 14.',
        'fast' => ['face' => true, 'arms' => false, 'speech' => true],
        'correct' => 'transport',
        'explanation' => 'NIH and CDC guidance: treat acute neuro deficits as stroke until proven otherwise — trauma may coexist; rapid stroke center transport with C-spine precautions when mechanism warrants.',
    ],
    [
        'title' => 'LVO Destination — Comprehensive Center',
        'scenario' => '42-year-old sudden severe headache, vomiting, left hemiplegia, decreased LOC. BP 198/110. Suspected hemorrhagic vs ischemic — LVO ischemic on differential.',
        'fast' => ['face' => true, 'arms' => true, 'speech' => true],
        'correct' => 'transport',
        'explanation' => 'CDC stroke triage supports routing to highest capable center for suspected LVO within thrombectomy window — notify early for neurointervention readiness (NIH stroke network recommendations).',
    ],
    [
        'title' => 'TIA vs Stroke — Resolved Deficit',
        'scenario' => 'Transient right arm weakness fully resolved over 10 minutes 30 minutes ago. Now normal exam, no FAST findings.',
        'fast' => ['face' => false, 'arms' => false, 'speech' => false],
        'correct' => 'transport',
        'explanation' => 'CDC and NIH classify transient focal neuro events as high-risk for stroke — TIA requires urgent evaluation within 24 hours; transport rather than dismiss resolved symptoms (CDC stroke FAST expansion to BE-FAST concepts).',
    ],
    [
        'title' => 'Seizure vs Postictal Stroke',
        'scenario' => 'Witnessed seizure now postictal. Persistent left hemiparesis and aphasia 20 minutes after seizure ended. First seizure at age 70.',
        'fast' => ['face' => true, 'arms' => true, 'speech' => true],
        'correct' => 'transport',
        'explanation' => 'NIH stroke guidance: new focal deficits persisting after seizure suggest stroke (Todd paralysis vs stroke) — transport for emergent imaging; do not attribute to postictal state without evaluation (CDC seizure/stroke overlap education).',
    ],
    [
        'title' => 'Vertigo — Posterior Circulation',
        'scenario' => 'Sudden vertigo, diplopia, dysarthria, unable to walk, onset 15 minutes ago. Face symmetric, arms strong, speech slurred.',
        'fast' => ['face' => false, 'arms' => false, 'speech' => true],
        'correct' => 'transport',
        'explanation' => 'CDC stroke awareness: posterior circulation strokes may lack classic FAST arm/face findings — sudden vertigo with cranial nerve signs is stroke until proven otherwise; transport to stroke center (NIH posterior circulation stroke resources).',
    ],
]);
