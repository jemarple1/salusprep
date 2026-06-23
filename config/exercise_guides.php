<?php

/**
 * Per-exercise how-to copy and optional review_slug overrides.
 *
 * @return array<string, array<string, array<string, string>>>
 */
return [

    'emt_basic' => [
        'soap-charting' => [
            'how_to' => 'Read each report sentence, then drag it into Subjective (what the patient says), Objective (what you measure or observe), Assessment (your clinical impression), or Plan (treatments and transport). Drop irrelevant noise into the trash. Submit when every sentence is placed.',
        ],
        'triage-start' => [
            'how_to' => 'Review each patient presentation and choose the START triage tag color (immediate, delayed, minor, or expectant). Apply the START algorithm: walking wounded first, then respirations, perfusion, and mental status.',
        ],
        'triage-jumpstart' => [
            'how_to' => 'Work through pediatric MCI presentations using JumpSTART. Select the tag color that matches the child\'s breathing, perfusion, and responsiveness — pediatric thresholds differ from adult START.',
        ],
        'triage-salt' => [
            'how_to' => 'Follow SALT step by step: Sort, Assess, Lifesaving interventions, Treatment/Transport. Pick the correct category or action at each decision point before moving to the next phase.',
        ],
        'triage-mci' => [
            'how_to' => 'Multiple patients compete for one ambulance. Tap the patient who should receive the next transport resource based on triage priority — immediate life threats before delayed or minor injuries.',
        ],
        'gcs-scenarios' => [
            'how_to' => 'For each head-injury scenario, select the best Eye, Verbal, and Motor response. The exercise totals your GCS score — compare against the expected neurologic picture.',
        ],
        'burn-scoring' => [
            'how_to' => 'Tap burned regions on the body diagram, then enter total body surface area (TBSA) using the rule of nines. Exclude simple erythema; count partial- and full-thickness burns only.',
        ],
        'stroke-scale' => [
            'how_to' => 'Review FAST findings (Face, Arms, Speech, Time) and vital context, then choose the best EMS action — usually rapid stroke-center transport when signs are positive.',
        ],
        'vital-signs' => [
            'how_to' => 'Read the vitals panel (BP, HR, RR, SpO₂, skin signs), identify the primary problem, and select the most appropriate first intervention within EMT-Basic scope.',
        ],
        'pharma-contraindications' => [
            'how_to' => 'Each prompt lists a medication and patient context. Answer YES if EMT-Basic protocol allows administration, NO if a contraindication or scope limit applies.',
        ],
        'pharma-assist' => [
            'how_to' => 'Read the scenario and decide whether you should assist the patient with their prescribed or protocol medication, withhold it, or monitor only. Choose the safest option.',
        ],
        'pharma-matching' => [
            'how_to' => 'Match the patient presentation to the correct EMT-Basic protocol medication. Consider chief complaint, allergies, and contraindications before selecting.',
        ],
        'pharma-outcomes' => [
            'how_to' => 'A medication was given — pick the finding that best shows improvement (or the expected therapeutic effect). Watch for wrong endpoints like unrelated vital changes.',
        ],
        'pharma-dosage' => [
            'how_to' => 'Calculate or recall the correct EMT-Basic dose and route from protocol. Enter or select the answer that matches local standard dosing for the drug shown.',
        ],
    ],

    'nclex_pn' => [
        'abc-prioritization' => [
            'how_to' => 'Read the clinical scenario and choose the first nursing action. Use ABC order — airway and breathing emergencies before circulation issues, then less urgent needs.',
        ],
        'adpie-nursing-process' => [
            'how_to' => 'Drag each nursing statement into Assessment, Diagnosis, Planning, Implementation, or Evaluation. Only one phase fits each statement — look for data vs. action cues.',
            'review_slug' => 'nursing-process-adpie',
        ],
        'maslow-prioritization' => [
            'how_to' => 'Rank patient needs from most to least urgent using Maslow\'s hierarchy. Physiologic survival needs come before safety, belonging, and self-esteem concerns.',
        ],
        'delegation' => [
            'how_to' => 'Decide whether each task stays with the RN, can go to an LPN/LVN, or is appropriate for UAP. Match scope, stability, and five-rights delegation rules.',
        ],
        'isolation-precautions' => [
            'how_to' => 'Match the diagnosis or situation to the correct transmission-based precaution (contact, droplet, airborne) plus standard precautions.',
            'review_slug' => 'infection-prevention',
        ],
        'medication-rights' => [
            'how_to' => 'Identify which medication right is at risk (patient, drug, dose, route, time, etc.) and select the safest nursing action before administering.',
            'review_slug' => 'medication-rights',
        ],
        'therapeutic-communication' => [
            'how_to' => 'Choose the response that best supports the patient therapeutically — open-ended, nonjudgmental, and within LPN scope. Avoid giving false reassurance or taking over.',
        ],
        'gcs-scoring' => [
            'how_to' => 'Score Eye, Verbal, and Motor for each neurologic scenario. Lower scores signal more severe injury; totals guide escalation and documentation.',
        ],
        'braden-scale' => [
            'how_to' => 'Rate each Braden subscale (sensory, moisture, activity, mobility, nutrition, friction). Pick values that match the patient description to estimate pressure-injury risk.',
        ],
        'morse-fall-scale' => [
            'how_to' => 'Score fall-risk factors on the Morse Fall Scale — history of falling, secondary diagnosis, ambulatory aids, IV, gait, and mental status. Total points guide fall precautions.',
        ],
    ],

    'paramedic' => [
        'patient-assessment' => [
            'how_to' => 'Branching scenario: each decision changes the patient\'s course. Choose assessments and interventions in logical order — primary survey first, then targeted secondary exam and treatment.',
        ],
        'rhythm-12lead' => [
            'how_to' => 'Match rhythm descriptions or ECG findings to the correct interpretation. Look for rate, regularity, P waves, QRS width, and ST-segment changes for STEMI clues.',
        ],
        'cardiology-treatment' => [
            'how_to' => 'Drag ACLS interventions into the correct priority order for bradycardia, tachycardia, or arrest algorithms. Life-threatening rhythms get pacing, shock, or epinephrine before secondary steps.',
        ],
        'airway-respiratory' => [
            'how_to' => 'Sort airway tools and ventilation strategies into the correct management categories — basic adjuncts, advanced airways, BVM, CPAP, and rescue techniques.',
        ],
        'pharmacology-mastery' => [
            'how_to' => 'Calculate weight-based medication doses using patient weight and FDA/protocol parameters. Enter the numeric answer within the allowed tolerance.',
        ],
        'shock-hemodynamics' => [
            'how_to' => 'Select all clinical findings that match the shock type presented — hypovolemic, cardiogenic, distributive, or obstructive. Multiple findings may apply.',
        ],
        'trauma-management' => [
            'how_to' => 'Rank multi-system trauma interventions from most to least urgent. Hemorrhage control and airway still precede detailed extremity care.',
        ],
        'stroke-neurology' => [
            'how_to' => 'Evaluate FAST and neuro exam findings, then choose stroke-center transport, blood glucose check, or other best next actions for ALS crews.',
        ],
        'medical-emergencies' => [
            'how_to' => 'Match each presentation to the most likely diagnosis and treatment pathway — consider classic symptom clusters and immediate ALS interventions.',
        ],
        'pediatrics-emergency' => [
            'how_to' => 'Use the patient\'s weight to calculate pediatric drug doses or fluid volumes. Double-check units (mg/kg, mL/kg) before submitting.',
        ],
        'obstetrics-neonatal' => [
            'how_to' => 'Branch through delivery and neonatal resuscitation steps. Each choice advances the scenario — follow NRP-style warming, drying, stimulation, and ventilation sequence.',
        ],
        'ems-operations-mci' => [
            'how_to' => 'Allocate limited transport resources during an MCI. Prioritize immediate patients per START/SALT while maintaining scene organization.',
        ],
        'soap-charting' => [
            'how_to' => 'Sort ALS narrative elements into SOAP sections or discard non-clinical filler. Advanced calls include more objective data, interventions, and reassessment findings.',
        ],
        'full-als-scenario' => [
            'how_to' => 'Multi-step call from dispatch to handoff. Make assessment, treatment, and transport decisions that keep the patient stable and document key milestones.',
        ],
        'adaptive-nrp-readiness' => [
            'how_to' => 'Choose all answers that apply for each certification-style item. Read every option — combinatorial questions may require two or three correct selections.',
        ],
    ],

];
