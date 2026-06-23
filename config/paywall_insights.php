<?php

use App\Support\CertificationLevel;

return [

    CertificationLevel::EMT_BASIC => [
        'struggle_intro' => 'Most EMT-Basic candidates lose points on the same few topic clusters — not because they skipped studying, but because the exam tests application under pressure.',
        'struggles' => [
            ['topic' => 'Airway & breathing', 'detail' => 'Choosing the right intervention when presentation is ambiguous — BVM vs OPA vs CPAP cues.'],
            ['topic' => 'Pharmacology', 'detail' => 'Indications, contraindications, and doses at the Basic scope — especially bronchodilators and aspirin.'],
            ['topic' => 'Trauma assessment', 'detail' => 'Primary vs secondary survey sequencing and when to load-and-go.'],
        ],
        'help_intro' => 'SalusPrep keeps you in the loop that mirrors the real exam:',
        'helps' => [
            'Adaptive quizzes that weight your weakest categories until accuracy climbs.',
            'Missed questions become flashcards with full rationales — not just the correct letter.',
            'Hands-on skills for vitals, GCS, triage, and pharmacology so concepts stick.',
            'A daily mock exam with pass/fail only — same adaptive pressure as test day.',
        ],
    ],

    CertificationLevel::EMT_ADVANCED => [
        'struggle_intro' => 'AEMT exams punish partial knowledge — knowing the skill but missing the scope-of-practice boundary is a common fail pattern.',
        'struggles' => [
            ['topic' => 'Advanced airway', 'detail' => 'When supraglottic airways are appropriate vs when to defer to ALS intercept.'],
            ['topic' => 'IV therapy', 'detail' => 'Flow rates, infiltration signs, and fluid choice in shock states.'],
            ['topic' => 'Medication administration', 'detail' => 'Expanded formulary at AEMT level — routes, doses, and patient selection.'],
        ],
        'help_intro' => 'Full Access builds the judgment the registry expects:',
        'helps' => [
            'Focus exams that hammer your lowest-accuracy categories first.',
            'Flashcards generated from every miss so rationales stick between sessions.',
            'Interactive skills drills for airway, pharmacology, and critical scenarios.',
            'Daily mock exams to practice adaptive termination under a time limit.',
        ],
    ],

    CertificationLevel::PARAMEDIC => [
        'struggle_intro' => 'Paramedic pass rates drop hardest on integration items — multiple systems in one stem, one best answer.',
        'struggles' => [
            ['topic' => 'Cardiology & 12-lead', 'detail' => 'STEMI recognition, medication selection, and post-ROSC care pathways.'],
            ['topic' => 'Pharmacology', 'detail' => 'Drip calculations, push-dose pressors, and sedative/analgesic pairing.'],
            ['topic' => 'Medical emergencies', 'detail' => 'Stroke, sepsis, and respiratory failure — who gets what first.'],
        ],
        'help_intro' => 'SalusPrep trains the full ALS decision loop:',
        'helps' => [
            'Category-weighted adaptive quizzes across all NREMT topic areas.',
            'Branching scenarios and skills exercises — not just multiple choice.',
            'Flashcards from your misses with protocol-level explanations.',
            'One daily mock exam with real adaptive pass/fail logic.',
        ],
    ],

    CertificationLevel::NCLEX_PN => [
        'struggle_intro' => 'NCLEX-PN rewards nursing judgment — prioritization, delegation, and safety — more than memorizing facts.',
        'struggles' => [
            ['topic' => 'Prioritization', 'detail' => 'Who to see first when every patient seems urgent — ABCs and Maslow in practice.'],
            ['topic' => 'Delegation & scope', 'detail' => 'What to assign to the UAP vs keep for the RN — and what to report immediately.'],
            ['topic' => 'Pharmacology & safety', 'detail' => 'Rights of medication administration and adverse-effect recognition.'],
        ],
        'help_intro' => 'Full Access targets the thinking style NCLEX tests:',
        'helps' => [
            'Adaptive quizzes weighted to your weak nursing-process categories.',
            'Flashcards built from missed items with clinical reasoning on the back.',
            'Skills drills for ADPIE, isolation precautions, scales, and delegation.',
            'Daily mock exams with adaptive difficulty — pass or fail only.',
        ],
    ],

];
