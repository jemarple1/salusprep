<?php

require_once __DIR__.'/helpers.php';

if (! function_exists('nclex_adpie_bank')) {
    /**
     * Build ADPIE scenario with sentence count scaled by level.
     *
     * @param  array<string, mixed>  $base
     * @return array<string, mixed>
     */
    function nclex_adpie_bank(array $base, int $level, int $index): array
    {
    $pool = $base['all_sentences'];
    $xPool = array_values(array_filter($pool, fn (array $s) => $s['section'] === 'X'));
    $adpiePool = array_values(array_filter($pool, fn (array $s) => $s['section'] !== 'X'));

    $targets = [
        1 => ['total' => 7, 'x' => 1],
        2 => ['total' => 8, 'x' => 2],
        3 => ['total' => 10, 'x' => 2],
        4 => ['total' => 12, 'x' => 3],
        5 => ['total' => 13, 'x' => 4],
    ];

    $target = $targets[$level];
    $xCount = min($target['x'], count($xPool));
    $adpieCount = $target['total'] - $xCount;

    $sections = ['A', 'D', 'P', 'I', 'E'];
    $selected = [];

    foreach ($sections as $section) {
        foreach ($adpiePool as $key => $sentence) {
            if ($sentence['section'] === $section) {
                $selected[] = $sentence;
                unset($adpiePool[$key]);
                break;
            }
        }
    }

    $adpiePool = array_values($adpiePool);
    while (count($selected) < $adpieCount && $adpiePool !== []) {
        $selected[] = array_shift($adpiePool);
    }

    $selectedX = array_slice($xPool, 0, $xCount);
    $sentences = array_merge($selected, $selectedX);

    return nclex_enrich([
        'title' => $base['title'],
        'scenario' => $base['scenario'],
        'explanation' => $base['explanation'],
        'sentences' => $sentences,
    ], $level, $index);
    }
}

return nclex_levels([
    fn (int $level, int $index) => nclex_adpie_bank([
        'title' => 'COPD Exacerbation Admission',
        'scenario' => 'A 71-year-old with COPD is admitted with increased dyspnea, productive cough, and SpO₂ 89% on room air.',
        'explanation' => 'Assessment gathers data; nursing diagnoses are problems supported by data; planning sets goals and interventions; implementation carries out care; evaluation compares outcomes to goals. Irrelevant or premature actions belong in trash.',
        'all_sentences' => [
            ['id' => 'a1', 'text' => 'Auscultate lung sounds anterior and posterior bilaterally.', 'section' => 'A'],
            ['id' => 'a2', 'text' => 'Note barrel chest, pursed-lip breathing, and use of accessory muscles.', 'section' => 'A'],
            ['id' => 'd1', 'text' => 'Ineffective airway clearance related to thick secretions and fatigue.', 'section' => 'D'],
            ['id' => 'd2', 'text' => 'Impaired gas exchange related to alveolar-capillary membrane changes.', 'section' => 'D'],
            ['id' => 'p1', 'text' => 'Client will maintain SpO₂ ≥ 92% on prescribed oxygen within 24 hours.', 'section' => 'P'],
            ['id' => 'p2', 'text' => 'Plan incentive spirometry every 2 hours while awake and CPT as ordered.', 'section' => 'P'],
            ['id' => 'i1', 'text' => 'Administer bronchodilator nebulizer treatment and oral corticosteroids as ordered.', 'section' => 'I'],
            ['id' => 'i2', 'text' => 'Position client in high Fowler and apply nasal cannula at provider-prescribed flow.', 'section' => 'I'],
            ['id' => 'e1', 'text' => 'Reassess breath sounds and SpO₂ 30 minutes after nebulizer treatment.', 'section' => 'E'],
            ['id' => 'e2', 'text' => 'Compare current work of breathing to admission baseline.', 'section' => 'E'],
            ['id' => 'x1', 'text' => 'Schedule outpatient pulmonary rehabilitation before discharge today.', 'section' => 'X'],
            ['id' => 'x2', 'text' => 'Diagnose COPD based on chest X-ray alone without provider interpretation.', 'section' => 'X'],
            ['id' => 'x3', 'text' => 'Discharge client when able to walk to the bathroom independently.', 'section' => 'X'],
            ['id' => 'x4', 'text' => 'Prescribe a long-term home oxygen liter flow without provider order.', 'section' => 'X'],
        ],
    ], $level, $index),

    fn (int $level, int $index) => nclex_adpie_bank([
        'title' => 'Fall Risk on Medical Unit',
        'scenario' => 'An 84-year-old with osteoporosis and a history of falls is admitted after a ground-level fall at home. Morse Fall Scale score is 75.',
        'explanation' => 'Fall prevention follows ADPIE: assess risk factors, diagnose risk, plan safety interventions, implement them, and evaluate whether falls are prevented.',
        'all_sentences' => [
            ['id' => 'a1', 'text' => 'Review Morse Fall Scale score and history of previous falls.', 'section' => 'A'],
            ['id' => 'a2', 'text' => 'Observe gait, balance, and orthostatic vital signs.', 'section' => 'A'],
            ['id' => 'd1', 'text' => 'Risk for falls related to unsteady gait and orthostatic hypotension.', 'section' => 'D'],
            ['id' => 'p1', 'text' => 'Client will remain free of falls during hospitalization.', 'section' => 'P'],
            ['id' => 'p2', 'text' => 'Place yellow fall-risk armband and bed alarm; plan toileting schedule every 2 hours.', 'section' => 'P'],
            ['id' => 'i1', 'text' => 'Keep bed in lowest position, call light within reach, and non-skid footwear on.', 'section' => 'I'],
            ['id' => 'i2', 'text' => 'Escort client to bathroom and ensure adequate lighting.', 'section' => 'I'],
            ['id' => 'e1', 'text' => 'Document absence of falls and reassess Morse score each shift.', 'section' => 'E'],
            ['id' => 'x1', 'text' => 'Apply wrist restraints to prevent all movement out of bed.', 'section' => 'X'],
            ['id' => 'x2', 'text' => 'Order physical therapy evaluation without notifying the provider.', 'section' => 'X'],
            ['id' => 'x3', 'text' => 'Risk for infection related to hip fracture.', 'section' => 'X'],
            ['id' => 'x4', 'text' => 'Evaluate Medicare coverage for skilled nursing placement.', 'section' => 'X'],
        ],
    ], $level, $index),

    fn (int $level, int $index) => nclex_adpie_bank([
        'title' => 'Stage 2 Pressure Injury',
        'scenario' => 'During skin assessment of a bedbound client, you find an open shallow ulcer on the coccyx with partial-thickness skin loss and a pink wound bed.',
        'explanation' => 'Pressure injury care requires accurate staging assessment, appropriate nursing diagnoses, evidence-based plan, wound implementation, and evaluation of healing progress.',
        'all_sentences' => [
            ['id' => 'a1', 'text' => 'Measure length, width, and depth; note drainage, odor, and periwound skin.', 'section' => 'A'],
            ['id' => 'a2', 'text' => 'Document partial-thickness skin loss consistent with stage 2 pressure injury.', 'section' => 'A'],
            ['id' => 'd1', 'text' => 'Impaired skin integrity related to prolonged pressure over bony prominence.', 'section' => 'D'],
            ['id' => 'd2', 'text' => 'Risk for infection related to open wound and immobility.', 'section' => 'D'],
            ['id' => 'p1', 'text' => 'Pressure injury will show signs of healing without increase in size within 1 week.', 'section' => 'P'],
            ['id' => 'p2', 'text' => 'Plan repositioning every 2 hours and pressure-redistribution surface.', 'section' => 'P'],
            ['id' => 'i1', 'text' => 'Cleanse wound with normal saline and apply moisture-retentive dressing per protocol.', 'section' => 'I'],
            ['id' => 'i2', 'text' => 'Implement turn schedule and offload coccyx with pillows.', 'section' => 'I'],
            ['id' => 'e1', 'text' => 'Reassess wound size and tissue type at next dressing change.', 'section' => 'E'],
            ['id' => 'e2', 'text' => 'Compare Braden score to prior shift to evaluate risk reduction.', 'section' => 'E'],
            ['id' => 'x1', 'text' => 'Stage the injury as stage 4 because the client is high risk.', 'section' => 'X'],
            ['id' => 'x2', 'text' => 'Apply hydrogen peroxide to the wound bed at each dressing change.', 'section' => 'X'],
            ['id' => 'x3', 'text' => 'Acute pain related to surgical incision on abdomen.', 'section' => 'X'],
            ['id' => 'x4', 'text' => 'Plan discharge home without wound care teaching.', 'section' => 'X'],
        ],
    ], $level, $index),

    fn (int $level, int $index) => nclex_adpie_bank([
        'title' => 'Post-operative Pain Management',
        'scenario' => 'A client is 8 hours post–abdominal hysterectomy, reporting incision pain 7/10 and guarding the abdomen.',
        'explanation' => 'Pain management uses ADPIE: assess pain characteristics, diagnose acute pain, plan multimodal relief, implement ordered analgesia and nonpharmacologic measures, evaluate effectiveness.',
        'all_sentences' => [
            ['id' => 'a1', 'text' => 'Assess pain location, intensity, quality, and effect on deep breathing.', 'section' => 'A'],
            ['id' => 'a2', 'text' => 'Inspect incision for redness, drainage, and separation.', 'section' => 'A'],
            ['id' => 'd1', 'text' => 'Acute pain related to surgical incision and tissue trauma.', 'section' => 'D'],
            ['id' => 'p1', 'text' => 'Client will report pain ≤ 4/10 within 1 hour of intervention.', 'section' => 'P'],
            ['id' => 'p2', 'text' => 'Plan scheduled analgesia before activity and splinting during coughing.', 'section' => 'P'],
            ['id' => 'i1', 'text' => 'Administer prescribed IV opioid and antiemetic as ordered.', 'section' => 'I'],
            ['id' => 'i2', 'text' => 'Teach splinting with pillow and assist with repositioning.', 'section' => 'I'],
            ['id' => 'e1', 'text' => 'Reassess pain score and sedation level 30 minutes after analgesic.', 'section' => 'E'],
            ['id' => 'e2', 'text' => 'Evaluate ability to use incentive spirometer after pain relief.', 'section' => 'E'],
            ['id' => 'x1', 'text' => 'Risk for falls related to chronic osteoarthritis.', 'section' => 'X'],
            ['id' => 'x2', 'text' => 'Withhold all opioids because the client appears sleepy.', 'section' => 'X'],
            ['id' => 'x3', 'text' => 'Diagnose surgical site infection without assessment findings.', 'section' => 'X'],
            ['id' => 'x4', 'text' => 'Implement discharge planning on day of surgery before evaluation.', 'section' => 'X'],
        ],
    ], $level, $index),

    fn (int $level, int $index) => nclex_adpie_bank([
        'title' => 'Dehydration in Older Adult',
        'scenario' => 'A 79-year-old is admitted with dizziness, dry mucous membranes, poor skin turgor, urine output 20 mL/hr, and sodium 152 mEq/L.',
        'explanation' => 'Fluid deficit care focuses on assessment of hydration status, nursing diagnosis, replacement plan, implementation of fluids and monitoring, and evaluation of electrolytes and intake/output.',
        'all_sentences' => [
            ['id' => 'a1', 'text' => 'Monitor vital signs, weight, mucous membranes, and capillary refill.', 'section' => 'A'],
            ['id' => 'a2', 'text' => 'Review intake/output and serum sodium, BUN, and creatinine.', 'section' => 'A'],
            ['id' => 'd1', 'text' => 'Deficient fluid volume related to inadequate oral intake and hypernatremia.', 'section' => 'D'],
            ['id' => 'p1', 'text' => 'Client will achieve balanced intake/output and stable vitals within 24 hours.', 'section' => 'P'],
            ['id' => 'p2', 'text' => 'Plan IV isotonic fluids per provider order and oral hydration schedule.', 'section' => 'P'],
            ['id' => 'i1', 'text' => 'Administer IV fluids at prescribed rate with pump and monitor for overload.', 'section' => 'I'],
            ['id' => 'i2', 'text' => 'Offer preferred fluids between meals and document intake.', 'section' => 'I'],
            ['id' => 'e1', 'text' => 'Reevaluate orthostatic vitals and urine output each shift.', 'section' => 'E'],
            ['id' => 'e2', 'text' => 'Compare repeat sodium level to admission value.', 'section' => 'E'],
            ['id' => 'x1', 'text' => 'Restrict all fluids to prevent further dehydration.', 'section' => 'X'],
            ['id' => 'x2', 'text' => 'Ineffective airway clearance related to pneumonia.', 'section' => 'X'],
            ['id' => 'x3', 'text' => 'Prescribe diuretics to treat hypernatremia independently.', 'section' => 'X'],
            ['id' => 'x4', 'text' => 'Evaluate client satisfaction with hospital meal options only.', 'section' => 'X'],
        ],
    ], $level, $index),

    fn (int $level, int $index) => nclex_adpie_bank([
        'title' => 'Hospital-Acquired Pneumonia',
        'scenario' => 'On hospital day 4, a post-stroke client develops fever 101.8°F (38.8°C), productive cough with yellow sputum, and crackles in the right lower lobe.',
        'explanation' => 'Pneumonia nursing care requires respiratory assessment, infection-related diagnoses, antibiotic and pulmonary hygiene planning, implementation, and evaluation of fever and breath sounds.',
        'all_sentences' => [
            ['id' => 'a1', 'text' => 'Auscultate lungs, measure SpO₂, and obtain sputum specimen as ordered.', 'section' => 'A'],
            ['id' => 'a2', 'text' => 'Assess cough effectiveness and work of breathing.', 'section' => 'A'],
            ['id' => 'd1', 'text' => 'Ineffective airway clearance related to inflammation and retained secretions.', 'section' => 'D'],
            ['id' => 'd2', 'text' => 'Hyperthermia related to infectious process.', 'section' => 'D'],
            ['id' => 'p1', 'text' => 'Client will demonstrate improved breath sounds and temperature < 100.4°F within 48 hours.', 'section' => 'P'],
            ['id' => 'p2', 'text' => 'Plan antibiotic administration, incentive spirometry, and head-of-bed elevation.', 'section' => 'P'],
            ['id' => 'i1', 'text' => 'Administer IV antibiotics and antipyretics on schedule.', 'section' => 'I'],
            ['id' => 'i2', 'text' => 'Encourage fluid intake and assist with repositioning for lung expansion.', 'section' => 'I'],
            ['id' => 'e1', 'text' => 'Monitor temperature curve and white blood cell trend.', 'section' => 'E'],
            ['id' => 'e2', 'text' => 'Reassess lung sounds after 48 hours of therapy.', 'section' => 'E'],
            ['id' => 'x1', 'text' => 'Risk for impaired skin integrity related to immobility only — no respiratory focus.', 'section' => 'X'],
            ['id' => 'x2', 'text' => 'Discontinue antibiotics when fever resolves one dose early.', 'section' => 'X'],
            ['id' => 'x3', 'text' => 'Plan immediate discharge without evaluating oxygen needs.', 'section' => 'X'],
            ['id' => 'x4', 'text' => 'Assess nutritional preferences for kosher meals.', 'section' => 'X'],
        ],
    ], $level, $index),

    fn (int $level, int $index) => nclex_adpie_bank([
        'title' => 'Preoperative Anxiety',
        'scenario' => 'A client scheduled for cholecystectomy in 2 hours reports nausea, trembling, and says, "I\'m terrified I won\'t wake up."',
        'explanation' => 'Preoperative anxiety care uses psychosocial assessment, anxiety diagnosis, planning for information and coping, implementation of therapeutic communication and ordered anxiolytics, and evaluation of anxiety level.',
        'all_sentences' => [
            ['id' => 'a1', 'text' => 'Assess anxiety level, vital signs, and understanding of the procedure.', 'section' => 'A'],
            ['id' => 'a2', 'text' => 'Note verbalized fears and nonverbal signs of distress.', 'section' => 'A'],
            ['id' => 'd1', 'text' => 'Anxiety related to unfamiliar environment and perceived loss of control.', 'section' => 'D'],
            ['id' => 'p1', 'text' => 'Client will verbalize reduced fear and demonstrate calm breathing before transport.', 'section' => 'P'],
            ['id' => 'p2', 'text' => 'Plan preoperative teaching reinforcement and presence of support person if allowed.', 'section' => 'P'],
            ['id' => 'i1', 'text' => 'Use calm voice, active listening, and clarify misconceptions about anesthesia.', 'section' => 'I'],
            ['id' => 'i2', 'text' => 'Administer ordered preoperative anxiolytic and maintain NPO status.', 'section' => 'I'],
            ['id' => 'e1', 'text' => 'Reassess anxiety rating and vital signs after intervention.', 'section' => 'E'],
            ['id' => 'e2', 'text' => 'Confirm client can state expected postoperative sensations.', 'section' => 'E'],
            ['id' => 'x1', 'text' => 'Tell the client not to worry because surgery always goes perfectly.', 'section' => 'X'],
            ['id' => 'x2', 'text' => 'Acute pain related to cholelithiasis — primary focus pre-op.', 'section' => 'X'],
            ['id' => 'x3', 'text' => 'Implement surgical time-out in the client room alone.', 'section' => 'X'],
            ['id' => 'x4', 'text' => 'Evaluate surgical wound healing on day of surgery.', 'section' => 'X'],
        ],
    ], $level, $index),

    fn (int $level, int $index) => nclex_adpie_bank([
        'title' => 'New-Onset Hyperglycemia',
        'scenario' => 'A client admitted for pancreatitis has fasting glucose 286 mg/dL, polyuria, polydipsia, and acetone breath odor.',
        'explanation' => 'Hyperglycemia management requires glucose assessment, nursing diagnoses, insulin and monitoring plan, implementation, and evaluation of glucose trends and ketosis resolution.',
        'all_sentences' => [
            ['id' => 'a1', 'text' => 'Monitor blood glucose per sliding scale orders and assess for ketosis signs.', 'section' => 'A'],
            ['id' => 'a2', 'text' => 'Review fluid balance, weight, and neurologic status.', 'section' => 'A'],
            ['id' => 'd1', 'text' => 'Risk for unstable blood glucose related to insulin deficiency and illness.', 'section' => 'D'],
            ['id' => 'd2', 'text' => 'Deficient fluid volume related to osmotic diuresis.', 'section' => 'D'],
            ['id' => 'p1', 'text' => 'Blood glucose will trend toward 140–180 mg/dL with ordered therapy.', 'section' => 'P'],
            ['id' => 'p2', 'text' => 'Plan subcutaneous insulin, carbohydrate-controlled diet when tolerated, and hourly checks if DKA suspected.', 'section' => 'P'],
            ['id' => 'i1', 'text' => 'Administer insulin and IV fluids per protocol; maintain NPO if ordered.', 'section' => 'I'],
            ['id' => 'i2', 'text' => 'Initiate hypoglycemia protocol supplies at bedside.', 'section' => 'I'],
            ['id' => 'e1', 'text' => 'Evaluate glucose response 2 hours after insulin adjustment.', 'section' => 'E'],
            ['id' => 'e2', 'text' => 'Compare repeat electrolytes and anion gap to baseline.', 'section' => 'E'],
            ['id' => 'x1', 'text' => 'Hold all insulin because the client is NPO without provider direction.', 'section' => 'X'],
            ['id' => 'x2', 'text' => 'Risk for falls related to age — unrelated primary problem.', 'section' => 'X'],
            ['id' => 'x3', 'text' => 'Plan discharge insulin teaching before glucose is stabilized.', 'section' => 'X'],
            ['id' => 'x4', 'text' => 'Diagnose type 1 diabetes definitively without provider and labs.', 'section' => 'X'],
        ],
    ], $level, $index),

    fn (int $level, int $index) => nclex_adpie_bank([
        'title' => 'Stroke with Hemiparesis',
        'scenario' => 'A client admitted 24 hours ago with left-sided weakness needs assistance with transfers and has difficulty swallowing thin liquids on bedside swallow screen.',
        'explanation' => 'Stroke rehabilitation nursing follows ADPIE for mobility and swallow safety while excluding actions outside PN scope or unrelated to the current plan.',
        'all_sentences' => [
            ['id' => 'a1', 'text' => 'Assess muscle strength, balance, swallow, and National Institutes of Health Stroke Scale per protocol.', 'section' => 'A'],
            ['id' => 'a2', 'text' => 'Evaluate ability to perform ADLs and need for assistive devices.', 'section' => 'A'],
            ['id' => 'd1', 'text' => 'Impaired physical mobility related to neuromuscular impairment.', 'section' => 'D'],
            ['id' => 'd2', 'text' => 'Risk for aspiration related to dysphagia.', 'section' => 'D'],
            ['id' => 'p1', 'text' => 'Client will transfer safely with one-person assist within 3 days.', 'section' => 'P'],
            ['id' => 'p2', 'text' => 'Plan PT/OT referral, aspiration precautions, and thickened liquids if ordered.', 'section' => 'P'],
            ['id' => 'i1', 'text' => 'Implement fall precautions, bed alarm, and upright position for oral intake.', 'section' => 'I'],
            ['id' => 'i2', 'text' => 'Assist with range-of-motion exercises and progressive mobility per PT plan.', 'section' => 'I'],
            ['id' => 'e1', 'text' => 'Document improved transfer ability compared to admission.', 'section' => 'E'],
            ['id' => 'e2', 'text' => 'Monitor for coughing during meals and notify if aspiration suspected.', 'section' => 'E'],
            ['id' => 'x1', 'text' => 'Allow thin liquids because the client is thirsty.', 'section' => 'X'],
            ['id' => 'x2', 'text' => 'Hyperthermia related to infection — not primary unless present.', 'section' => 'X'],
            ['id' => 'x3', 'text' => 'Prescribe tPA after 24 hours for this client.', 'section' => 'X'],
            ['id' => 'x4', 'text' => 'Evaluate Medicare Part D coverage during acute stroke care.', 'section' => 'X'],
        ],
    ], $level, $index),

    fn (int $level, int $index) => nclex_adpie_bank([
        'title' => 'Surgical Site Infection Prevention',
        'scenario' => 'A client is post-op day 2 from colorectal surgery with a closed abdominal incision, temp 99.8°F (37.7°C), and WBC mildly elevated.',
        'explanation' => 'Infection prevention uses assessment for early SSI signs, risk diagnosis, aseptic plan, implementation of wound care and antibiotics if ordered, and evaluation of healing and labs.',
        'all_sentences' => [
            ['id' => 'a1', 'text' => 'Inspect incision for erythema, warmth, drainage, and separation.', 'section' => 'A'],
            ['id' => 'a2', 'text' => 'Monitor temperature, WBC, and client report of increased pain at site.', 'section' => 'A'],
            ['id' => 'd1', 'text' => 'Risk for infection related to surgical wound and altered immune response.', 'section' => 'D'],
            ['id' => 'p1', 'text' => 'Incision will remain clean, dry, and without purulent drainage during hospitalization.', 'section' => 'P'],
            ['id' => 'p2', 'text' => 'Plan aseptic dressing changes, hand hygiene, and timely antibiotic administration.', 'section' => 'P'],
            ['id' => 'i1', 'text' => 'Perform dressing change with sterile technique and document appearance.', 'section' => 'I'],
            ['id' => 'i2', 'text' => 'Administer prophylactic antibiotics on schedule and maintain glycemic control per orders.', 'section' => 'I'],
            ['id' => 'e1', 'text' => 'Compare daily temperature and WBC trend to baseline.', 'section' => 'E'],
            ['id' => 'e2', 'text' => 'Reassess pain localized to incision versus generalized.', 'section' => 'E'],
            ['id' => 'x1', 'text' => 'Remove dressing and leave incision open to air permanently.', 'section' => 'X'],
            ['id' => 'x2', 'text' => 'Anxiety related to hospitalization — not the focused infection plan.', 'section' => 'X'],
            ['id' => 'x3', 'text' => 'Diagnose wound dehiscence without visualizing the incision.', 'section' => 'X'],
            ['id' => 'x4', 'text' => 'Implement client teaching about colostomy care before evaluating incision status.', 'section' => 'X'],
        ],
    ], $level, $index),
]);
