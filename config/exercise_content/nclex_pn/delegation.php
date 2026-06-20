<?php

require_once __DIR__.'/helpers.php';

return nclex_levels([
    [
        'title' => 'Routine Vital Signs — Stable Client',
        'scenario' => 'The RN is managing a post-op client with new onset chest pain. A second client on the unit is stable, ambulating independently, and due for routine vital signs q4h.',
        'question' => 'Which task is appropriate to delegate to the UAP?',
        'options' => [
            'uap' => 'Obtain routine vital signs on the stable ambulatory client',
            'lpn' => 'Assess the stable client\'s surgical incision for infection',
            'rn' => 'Retain all tasks; delegate nothing this shift',
            'uap_assess' => 'Have the UAP perform the initial admission assessment on the stable client',
        ],
        'correct' => 'uap',
        'explanation' => 'Routine vital signs on a stable client are within UAP scope when the RN has assessed stability and provided clear instructions. Assessment, evaluation, and teaching remain RN responsibilities.',
        'level_options' => [
            4 => [
                'uap' => 'Obtain routine vital signs on the stable ambulatory client',
                'lpn' => 'Have the LPN develop the stable client\'s plan of care',
                'rn' => 'Retain vital signs because delegation is never allowed',
                'uap_assess' => 'Delegate the stable client\'s pain assessment to the UAP',
            ],
            5 => [
                'uap' => 'Obtain routine vital signs on the stable ambulatory client',
                'lpn' => 'Assign the LPN to perform the admission history on a new admit',
                'rn' => 'Ask the UAP to evaluate effectiveness of PRN analgesia',
                'uap_assess' => 'Have the UAP teach inhaler technique to the stable client',
            ],
        ],
    ],
    [
        'title' => 'IV Push Medication',
        'scenario' => 'A client with atrial fibrillation has an order for IV push metoprolol. The LPN on the team is certified in IV therapy per facility policy. The UAP offers to assist.',
        'question' => 'Who should administer the IV push medication?',
        'options' => [
            'uap' => 'UAP — medication administration is in UAP scope with supervision',
            'lpn' => 'LPN — if within state scope and facility policy allow IV push beta-blockers',
            'rn' => 'RN must administer all IV push medications in every setting',
            'uap_supervised' => 'UAP may give IV push with RN standing at bedside',
        ],
        'correct' => 'lpn',
        'explanation' => 'IV medications, especially IV push, are outside UAP scope. Depending on state nurse practice act and facility policy, an IV-certified LPN may administer certain IV medications; the RN retains accountability and must verify competency.',
        'level_options' => [
            4 => [
                'uap' => 'UAP after completing a facility online module',
                'lpn' => 'LPN — if within state scope and facility policy allow IV push beta-blockers',
                'rn' => 'Only the charge RN may ever give IV medications',
                'uap_supervised' => 'UAP may push the medication while RN documents',
            ],
            5 => [
                'uap' => 'UAP — IV push is a technical skill like bathing',
                'lpn' => 'LPN — if within state scope and facility policy allow IV push beta-blockers',
                'rn' => 'RN must administer; LPN may never touch IV lines',
                'uap_supervised' => 'UAP may administer if the client is stable',
            ],
        ],
    ],
    [
        'title' => 'Insulin Teaching — New Diagnosis',
        'scenario' => 'A client newly diagnosed with type 1 diabetes must learn subcutaneous insulin injection, glucose monitoring, and hypoglycemia recognition before discharge tomorrow.',
        'question' => 'Which assignment is appropriate?',
        'options' => [
            'uap' => 'Delegate return demonstration of insulin injection to the UAP',
            'lpn' => 'Assign the LPN to reinforce insulin skills after RN initial teaching',
            'rn' => 'RN performs initial teaching; this cannot be delegated',
            'lpn_initial' => 'LPN completes all initial diabetes teaching independently on admission',
        ],
        'correct' => 'rn',
        'explanation' => 'Initial client teaching requiring nursing judgment and evaluation of learning is an RN responsibility. An LPN may reinforce previously taught content per scope, but new complex teaching for a new diagnosis stays with the RN.',
        'level_options' => [
            4 => [
                'uap' => 'UAP demonstrates injection technique learned from a video',
                'lpn' => 'LPN teaches insulin dosing adjustments independently',
                'rn' => 'RN performs initial teaching; this cannot be delegated',
                'lpn_initial' => 'LPN completes all initial diabetes teaching independently on admission',
            ],
            5 => [
                'uap' => 'UAP evaluates whether the client is ready for discharge teaching',
                'lpn' => 'LPN prescribes the insulin sliding scale',
                'rn' => 'RN performs initial teaching; this cannot be delegated',
                'lpn_initial' => 'LPN performs admission assessment and develops the teaching plan alone',
            ],
        ],
    ],
    [
        'title' => 'Ambulation — Stable Post-op Day 2',
        'scenario' => 'A client is post-op day 2 from cholecystectomy, stable vitals, pain 3/10 with oral analgesics, and physician ordered progressive ambulation with assist.',
        'question' => 'What may the RN appropriately delegate?',
        'options' => [
            'uap' => 'UAP ambulates the client in the hall with gait belt after RN assesses readiness',
            'lpn' => 'LPN evaluates whether ambulation worsened incision pain',
            'rn' => 'No delegation — RN must ambulate every client personally',
            'uap_assess' => 'UAP determines weight-bearing status before ambulation',
        ],
        'correct' => 'uap',
        'explanation' => 'Ambulation of a stable client with a clear activity order is appropriate for UAP after RN assessment of readiness and instruction on parameters to report. Evaluation of pain response and clinical judgment remain RN/LPN functions per scope.',
        'level_options' => [
            4 => [
                'uap' => 'UAP ambulates the client in the hall with gait belt after RN assesses readiness',
                'lpn' => 'LPN decides the client is too weak and cancels ambulation independently',
                'rn' => 'RN must ambulate all post-op clients without exception',
                'uap_assess' => 'UAP performs neurovascular assessment before and after ambulation',
            ],
            5 => [
                'uap' => 'UAP ambulates the client in the hall with gait belt after RN assesses readiness',
                'lpn' => 'LPN develops the progressive mobility care plan alone on admission',
                'rn' => 'Delegation is unsafe for any post-operative client',
                'uap_assess' => 'UAP interprets orthostatic vital sign changes and changes orders',
            ],
        ],
    ],
    [
        'title' => 'Wound VAC Dressing Change',
        'scenario' => 'A client has an complex abdominal wound requiring negative-pressure wound therapy dressing change every 48 hours per protocol. The LPN has completed facility competency validation for wound VAC.',
        'question' => 'Which delegation decision is correct?',
        'options' => [
            'uap' => 'UAP may change the wound VAC dressing with step-by-step instructions',
            'lpn' => 'LPN may perform the dressing change per validated competency and provider order',
            'rn' => 'Only the wound ostomy nurse may touch the device — RN retains task',
            'uap_clean' => 'UAP cleans the wound bed and RN applies the VAC only',
        ],
        'correct' => 'lpn',
        'explanation' => 'Complex sterile dressing changes may be within LPN scope when competency is validated and state law permits. UAP cannot perform sterile wound care requiring nursing assessment during the procedure.',
        'level_options' => [
            4 => [
                'uap' => 'UAP changes the sponge and connects tubing after RN leaves',
                'lpn' => 'LPN may perform the dressing change per validated competency and provider order',
                'rn' => 'RN must perform every wound VAC change regardless of competency',
                'uap_clean' => 'UAP assesses granulation tissue and documents staging',
            ],
            5 => [
                'uap' => 'UAP evaluates wound healing and adjusts negative pressure independently',
                'lpn' => 'LPN may perform the dressing change per validated competency and provider order',
                'rn' => 'LPN may never perform any sterile procedure',
                'uap_clean' => 'UAP teaches family to perform VAC at home without RN oversight',
            ],
        ],
    ],
    [
        'title' => 'Feeding Client with Dysphagia',
        'scenario' => 'A client with stroke has nectar-thick liquids, pureed diet, and requires setup and cueing during meals. Swallow precautions are documented. Client is alert and can follow simple commands.',
        'question' => 'Which task can be delegated to the UAP?',
        'options' => [
            'uap' => 'UAP assists with meal setup and feeding with RN/LPN instruction on aspiration precautions',
            'lpn' => 'LPN performs initial swallow screening and changes diet texture independently',
            'rn' => 'RN must feed all dysphagia clients — no delegation permitted',
            'uap_thin' => 'UAP may offer thin liquids if the client appears thirsty',
        ],
        'correct' => 'uap',
        'explanation' => 'Feeding and meal assistance are UAP tasks when the RN/LPN has assessed swallow status, provided the correct diet, and given clear safety instructions. Initial swallow assessment and diet prescription require nursing judgment.',
        'level_options' => [
            4 => [
                'uap' => 'UAP assists with meal setup and feeding with RN/LPN instruction on aspiration precautions',
                'lpn' => 'LPN delegates swallow evaluation to the speech therapist via UAP',
                'rn' => 'Any dysphagia client requires RN-only feeding',
                'uap_thin' => 'UAP may offer thin liquids if the client appears thirsty',
            ],
            5 => [
                'uap' => 'UAP assists with meal setup and feeding with RN/LPN instruction on aspiration precautions',
                'lpn' => 'LPN orders a modified barium swallow study without provider',
                'rn' => 'UAP may determine when client is ready for regular diet',
                'uap_thin' => 'UAP evaluates cough reflex after meals and changes diet',
            ],
        ],
    ],
    [
        'title' => 'Indwelling Catheter Care',
        'scenario' => 'Several clients on the unit have indwelling urinary catheters. One client needs perineal care and a clean catch urine specimen per routine protocol. The client is stable without fever or confusion.',
        'question' => 'What is appropriate to delegate to the UAP?',
        'options' => [
            'uap' => 'Perineal hygiene and obtaining a routine urine specimen from the drainage bag per protocol',
            'lpn' => 'LPN inserts a new urinary catheter without an order',
            'rn' => 'RN must perform all catheter care — delegation prohibited',
            'uap_insert' => 'UAP inserts the indwelling catheter after online training',
        ],
        'correct' => 'uap',
        'explanation' => 'Routine perineal care and specimen collection from an existing catheter per protocol are UAP tasks when the RN supervises and the client is stable. Catheter insertion and assessment for complications require nursing scope.',
        'level_options' => [
            4 => [
                'uap' => 'Perineal hygiene and obtaining a routine urine specimen from the drainage bag per protocol',
                'lpn' => 'LPN interprets cloudy urine and starts antibiotics independently',
                'rn' => 'All urinary tasks require RN due to infection risk',
                'uap_insert' => 'UAP inserts the indwelling catheter after online training',
            ],
            5 => [
                'uap' => 'Perineal hygiene and obtaining a routine urine specimen from the drainage bag per protocol',
                'lpn' => 'LPN evaluates CAUTI bundle compliance and changes physician orders',
                'rn' => 'UAP may remove catheters when output decreases',
                'uap_insert' => 'UAP assesses need for catheter and documents indication',
            ],
        ],
    ],
    [
        'title' => 'Evaluating PRN Analgesic',
        'scenario' => 'A client received IV morphine 4 mg for severe post-op pain 30 minutes ago. The RN is assisting with a code on another unit. The client now rates pain 2/10 and is resting comfortably.',
        'question' => 'Which task should NOT be delegated?',
        'options' => [
            'uap' => 'UAP documents the client\'s pain score on the flow sheet',
            'lpn' => 'LPN evaluates effectiveness of the morphine and respiratory status',
            'rn' => 'RN evaluation of analgesic response — cannot delegate evaluation',
            'lpn_vitals' => 'LPN obtains blood pressure and respirations after opioid',
        ],
        'correct' => 'rn',
        'explanation' => 'Evaluation of medication effectiveness and clinical response requires nursing judgment and remains RN accountability (LPN may contribute assessments per scope, but overall evaluation of opioid response stays with RN). UAP may document objective data, not evaluate outcomes.',
        'level_options' => [
            4 => [
                'uap' => 'UAP decides no further opioid is needed and withholds PRN dose',
                'lpn' => 'LPN evaluates sedation and respiratory depression after opioid',
                'rn' => 'RN evaluation of analgesic response — cannot delegate evaluation',
                'lpn_vitals' => 'LPN obtains blood pressure and respirations after opioid',
            ],
            5 => [
                'uap' => 'UAP evaluates whether pain goal is met and adjusts care plan',
                'lpn' => 'LPN prescribes naloxone if respirations are 8/min',
                'rn' => 'RN evaluation of analgesic response — cannot delegate evaluation',
                'lpn_vitals' => 'LPN may never take vital signs after opioids',
            ],
        ],
    ],
    [
        'title' => 'Tracheostomy Suctioning — Stable Client',
        'scenario' => 'A long-term care transfer client has a mature tracheostomy, thick secretions, and a protocol for PRN suctioning q4h and as needed. The LPN has documented tracheostomy competency.',
        'question' => 'Which assignment follows NCLEX delegation principles?',
        'options' => [
            'uap' => 'UAP performs tracheostomy suctioning using a checklist',
            'lpn' => 'LPN performs tracheostomy suctioning per competency and established care plan',
            'rn' => 'Only RT or RN may ever suction — LPN cannot',
            'uap_setup' => 'UAP suctions and evaluates need for oxygen increase independently',
        ],
        'correct' => 'lpn',
        'explanation' => 'Tracheostomy care and suctioning are typically within LPN scope when competency is validated and the client is stable with an established airway plan. UAP cannot perform invasive airway suctioning or evaluate respiratory response.',
        'level_options' => [
            4 => [
                'uap' => 'UAP performs suctioning after watching one demonstration',
                'lpn' => 'LPN performs tracheostomy suctioning per competency and established care plan',
                'rn' => 'Suctioning always requires two RNs present',
                'uap_setup' => 'UAP suctions and evaluates need for oxygen increase independently',
            ],
            5 => [
                'uap' => 'UAP changes tracheostomy ties and assesses stoma for infection alone',
                'lpn' => 'LPN performs tracheostomy suctioning per competency and established care plan',
                'rn' => 'LPN scope never includes any airway procedures',
                'uap_setup' => 'UAP interprets arterial blood gases and adjusts vent settings',
            ],
        ],
    ],
    [
        'title' => 'Blood Glucose Monitoring — Stable Type 2',
        'scenario' => 'A stable type 2 diabetic on sliding-scale insulin needs AC/HS fingerstick glucose checks. The client is alert, has intact sensation, and the RN verified technique at admission.',
        'question' => 'Which delegation is appropriate per current practice?',
        'options' => [
            'uap' => 'UAP performs fingerstick glucose checks and reports results to the nurse',
            'lpn' => 'LPN adjusts insulin sliding scale without provider notification',
            'rn' => 'Glucose monitoring can never be delegated in the hospital',
            'uap_insulin' => 'UAP administers subcutaneous insulin from the sliding scale',
        ],
        'correct' => 'uap',
        'explanation' => 'Point-of-care glucose monitoring is commonly delegated to UAP in acute care when the RN supervises, interprets results, and administers insulin. UAP cannot administer medications or independently adjust insulin.',
        'level_options' => [
            4 => [
                'uap' => 'UAP performs fingerstick glucose checks and reports results to the nurse',
                'lpn' => 'LPN changes insulin orders based on one elevated reading',
                'rn' => 'Only laboratory staff may perform glucose checks',
                'uap_insulin' => 'UAP administers subcutaneous insulin from the sliding scale',
            ],
            5 => [
                'uap' => 'UAP performs fingerstick glucose checks and reports results to the nurse',
                'lpn' => 'LPN evaluates overall glycemic control and discharges client independently',
                'rn' => 'Delegation of glucose checks violates Joint Commission standards always',
                'uap_insulin' => 'UAP teaches family to titrate insulin at home during hospital stay',
            ],
        ],
    ],
]);
