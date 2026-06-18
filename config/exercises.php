<?php

return [

    'emt_basic' => [
        ['slug' => 'soap-charting', 'title' => 'SOAP Exercise', 'description' => 'Drag report sentences into Subjective, Objective, Assessment, and Plan.', 'category' => 'Documentation', 'color' => 'ems', 'type' => 'soap', 'ui' => 'soap', 'icon' => 'clipboard'],
        ['slug' => 'triage-start', 'title' => 'START Triage', 'description' => 'Assign the correct START tag color for each patient presentation.', 'category' => 'Triage', 'color' => 'rescue', 'type' => 'interactive', 'ui' => 'triage-tags', 'system' => 'start', 'icon' => 'triage'],
        ['slug' => 'triage-jumpstart', 'title' => 'JumpSTART Triage', 'description' => 'Pediatric MCI triage — pick the correct tag color.', 'category' => 'Triage', 'color' => 'rescue', 'type' => 'interactive', 'ui' => 'triage-tags', 'system' => 'jumpstart', 'icon' => 'pediatric'],
        ['slug' => 'triage-salt', 'title' => 'SALT Triage', 'description' => 'Work through Sort, Assess, Lifesaving interventions, and Transport.', 'category' => 'Triage', 'color' => 'rescue', 'type' => 'interactive', 'ui' => 'salt-triage', 'icon' => 'salt'],
        ['slug' => 'triage-mci', 'title' => 'Multi-Patient MCI', 'description' => 'Tap the patient who needs the next transport resource.', 'category' => 'Triage', 'color' => 'rescue', 'type' => 'interactive', 'ui' => 'triage-priority', 'icon' => 'patients'],
        ['slug' => 'gcs-scenarios', 'title' => 'GCS Scoring', 'description' => 'Select eye, verbal, and motor scores for head-injury patients.', 'category' => 'Assessment', 'color' => 'medic', 'type' => 'interactive', 'ui' => 'gcs-picker', 'icon' => 'brain'],
        ['slug' => 'burn-scoring', 'title' => 'Burn Scoring', 'description' => 'Mark burned areas on the body diagram to estimate TBSA.', 'category' => 'Assessment', 'color' => 'safety', 'type' => 'interactive', 'ui' => 'burn-map', 'icon' => 'burn'],
        ['slug' => 'stroke-scale', 'title' => 'Stroke Scale', 'description' => 'Evaluate FAST findings and choose the best action.', 'category' => 'Assessment', 'color' => 'medic', 'type' => 'interactive', 'ui' => 'stroke-fast', 'icon' => 'stroke'],
        ['slug' => 'vital-signs', 'title' => 'Vital Sign Interpretation', 'description' => 'Read the vitals panel and choose the best intervention.', 'category' => 'Assessment', 'color' => 'ems', 'type' => 'interactive', 'ui' => 'vitals-panel', 'icon' => 'vitals'],
        ['slug' => 'pharma-contraindications', 'title' => 'Pharma — Contraindications', 'description' => 'Fast YES/NO drills: is it safe to give this EMT-Basic medication?', 'category' => 'Pharmacology', 'color' => 'pharma', 'type' => 'interactive', 'ui' => 'pharma-yesno', 'icon' => 'pill'],
        ['slug' => 'pharma-assist', 'title' => 'Pharma — Assist or Not', 'description' => 'Scenario-based decisions: should you assist with the medication?', 'category' => 'Pharmacology', 'color' => 'pharma', 'type' => 'interactive', 'ui' => 'pharma-choice', 'icon' => 'pill'],
        ['slug' => 'pharma-matching', 'title' => 'Pharma — Symptom Match', 'description' => 'Match the presentation to the correct EMT-Basic protocol drug.', 'category' => 'Pharmacology', 'color' => 'pharma', 'type' => 'interactive', 'ui' => 'pharma-choice', 'icon' => 'pill'],
        ['slug' => 'pharma-outcomes', 'title' => 'Pharma — What Improves?', 'description' => 'After giving a medication, which finding shows it is working?', 'category' => 'Pharmacology', 'color' => 'pharma', 'type' => 'interactive', 'ui' => 'pharma-choice', 'icon' => 'pill'],
        ['slug' => 'pharma-dosage', 'title' => 'Pharma — Dosages', 'description' => 'Dose and route questions for EMT-Basic protocol medications.', 'category' => 'Pharmacology', 'color' => 'pharma', 'type' => 'interactive', 'ui' => 'pharma-choice', 'icon' => 'pill'],
    ],

];
