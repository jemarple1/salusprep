<?php

return [
    'scenarios' => [
        [
            'title' => 'Nitroglycerin + PDE-5 inhibitor',
            'drug' => 'Nitroglycerin',
            'prompt' => '52-year-old with chest pain. Patient took sildenafil (Viagra) 8 hours ago. Blood pressure 128/78. Is it safe to assist with nitroglycerin per protocol?',
            'correct' => 'no',
            'explanation' => 'Recent PDE-5 inhibitor use (within 24–48 hours per protocol) is a contraindication to nitroglycerin due to severe hypotension risk.',
        ],
        [
            'title' => 'Oral glucose — altered LOC',
            'drug' => 'Oral Glucose',
            'prompt' => 'Adult diabetic found confused and diaphoretic. Glucose 48 mg/dL. Patient is lethargic and cannot reliably swallow. Safe to give oral glucose?',
            'correct' => 'no',
            'explanation' => 'Oral glucose requires the ability to swallow and protect the airway. Use IV glucose or IM glucagon per protocol when the patient cannot swallow safely.',
        ],
        [
            'title' => 'Aspirin — cardiac chest pain',
            'drug' => 'Aspirin',
            'prompt' => '58-year-old with pressure-like chest pain, no aspirin allergy, no active GI bleed. Is assisting with aspirin appropriate?',
            'correct' => 'yes',
            'explanation' => 'Aspirin is indicated for suspected acute coronary syndrome when the patient has no allergy or active bleeding contraindication.',
        ],
        [
            'title' => 'Diphenhydramine — known allergy',
            'drug' => 'Diphenhydramine',
            'prompt' => 'Patient with hives states they are allergic to diphenhydramine (Benadryl). No airway compromise yet. Safe to assist with diphenhydramine?',
            'correct' => 'no',
            'explanation' => 'Known allergy to diphenhydramine is an absolute contraindication. Monitor closely and treat per protocol if anaphylaxis develops.',
        ],
        [
            'title' => 'Activated charcoal — caustic ingestion',
            'drug' => 'Activated Charcoal',
            'prompt' => 'Teenager ingested drain cleaner 20 minutes ago. Awake, speaking in full sentences. Safe to give activated charcoal?',
            'correct' => 'no',
            'explanation' => 'Activated charcoal is contraindicated for caustic or corrosive ingestions — it does not bind these agents and may complicate endoscopy.',
        ],
    ],
];
