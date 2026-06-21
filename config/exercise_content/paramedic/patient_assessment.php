<?php

require_once __DIR__.'/helpers.php';

return paramedic_levels([
    [
        'title' => 'ACS Chest Pain — Primary Survey',
        'scenario' => 'A 58-year-old male reports substernal pressure radiating to the left arm for 30 minutes. He is pale, diaphoretic, and anxious. SpO₂ 94% on room air, BP 148/92, HR 96.',
        'steps' => [
            [
                'prompt' => 'What is your next best action after confirming scene safety?',
                'options' => [
                    'nitro' => 'Administer nitroglycerin before any other intervention',
                    'assess' => 'Apply oxygen, obtain 12-lead ECG, establish IV access, and prepare aspirin per protocol',
                    'transport' => 'Load immediately without assessment to minimize door-to-balloon time',
                    'morphine' => 'Give IV morphine for pain before cardiac monitoring',
                ],
                'correct' => 'assess',
                'outcomes' => [
                    'nitro' => 'You give nitroglycerin without a 12-lead. Pain briefly improves but the monitor shows no ischemic changes captured yet.',
                    'assess' => 'Oxygen applied, 12-lead shows ST elevation in V2–V4, IV established. You confirm anterior STEMI and prepare aspirin.',
                    'transport' => 'You load without monitoring. En route, the patient becomes hypotensive and you lack a baseline 12-lead.',
                    'morphine' => 'Morphine reduces pain but masks symptom progression; no ECG acquired before transport decision.',
                ],
            ],
            [
                'prompt' => 'The 12-lead confirms anterior STEMI. What is your priority now?',
                'options' => [
                    'aspirin' => 'Give aspirin per protocol, activate STEMI alert, and transport to PCI-capable center',
                    'wait' => 'Wait for online medical control before any medication',
                    'fluids' => '500 mL fluid bolus before aspirin',
                    'discharge' => 'Offer refusal because pain is improving',
                ],
                'correct' => 'aspirin',
                'outcomes' => [
                    'aspirin' => 'STEMI alert activated. PCI center notified. Patient remains stable en route with continuous monitoring.',
                    'wait' => 'Delays reperfusion notification. Hospital receives no pre-arrival STEMI alert.',
                    'fluids' => 'Unnecessary fluid without shock may worsen ischemia; aspirin and alert delayed.',
                    'discharge' => 'Patient develops ventricular tachycardia minutes later — a life-threatening error.',
                ],
            ],
        ],
        'explanation' => 'NHTSA EMS education emphasizes systematic assessment and early ACS recognition. Oxygen for hypoxemia, immediate 12-lead acquisition, vascular access, aspirin when not contraindicated, and STEMI alert with PCI-capable transport align with NIH NHLBI acute MI guidance.',
    ],
    [
        'title' => 'Altered Mental Status — Hypoglycemia',
        'scenario' => 'A 72-year-old diabetic is found confused at home. GCS 12 (E3V4M5), skin cool and clammy, HR 110, RR 18. Family reports missed lunch.',
        'steps' => [
            [
                'prompt' => 'Which action should be performed first during the primary assessment?',
                'options' => [
                    'glucose' => 'Check blood glucose and treat hypoglycemia if indicated',
                    'iv' => 'Establish two large-bore IVs before any other intervention',
                    'stroke' => 'Request immediate stroke center bypass for CT',
                    'restraint' => 'Apply soft restraints to prevent injury',
                ],
                'correct' => 'glucose',
                'outcomes' => [
                    'glucose' => 'Fingerstick reads 48 mg/dL. You treat per protocol and mental status improves within minutes.',
                    'iv' => 'IV access delayed glucose check. Patient becomes more obtunded.',
                    'stroke' => 'Stroke bypass initiated without treating reversible hypoglycemia.',
                    'restraint' => 'Restraints applied; glucose remains unchecked and patient deteriorates.',
                ],
            ],
            [
                'prompt' => 'After treatment, glucose is 92 mg/dL and GCS improves to 14. What is next?',
                'options' => [
                    'transport' => 'Transport for evaluation — recurrent hypoglycemia and cause need assessment',
                    'leave' => 'Leave at home since patient is alert now',
                    'fast' => 'Withhold food until ED only',
                    'insulin' => 'Administer insulin to prevent rebound hyperglycemia',
                ],
                'correct' => 'transport',
                'outcomes' => [
                    'transport' => 'Patient remains stable. ED evaluates medication regimen and nutrition plan.',
                    'leave' => 'Patient becomes hypoglycemic again overnight per CDC diabetes emergency patterns.',
                    'fast' => 'Patient becomes symptomatic from hunger en route if left without oral intake plan.',
                    'insulin' => 'Iatrogenic hyperglycemia risk — inappropriate in field after hypoglycemia treatment.',
                ],
            ],
        ],
        'explanation' => 'CDC and NIH diabetes emergency guidance requires rapid point-of-care glucose testing for AMS in diabetics. Treat hypoglycemia after airway and breathing support, then transport for evaluation of precipitating cause per NHTSA EMS assessment principles.',
    ],
    [
        'title' => 'Syncope with Red Flags',
        'scenario' => 'A 45-year-old fainted at work and is now alert. She reports palpitations before the event, brief witnessed tonic-clonic movement, and BP 88/54 with HR 48 irregular.',
        'steps' => [
            [
                'prompt' => 'What is the priority next step in your assessment?',
                'options' => [
                    'discharge' => 'Release to supervisor — syncope resolved and patient is alert',
                    'cardiac' => 'Apply cardiac monitor, 12-lead ECG, IV access, and prepare transport',
                    'food' => 'Offer juice and oral fluids for presumed dehydration',
                    'orthostatics' => 'Perform orthostatic vitals only and observe 20 minutes',
                ],
                'correct' => 'cardiac',
                'outcomes' => [
                    'discharge' => 'Patient rearrests with bradycardia in the parking lot.',
                    'cardiac' => 'Monitor shows third-degree AV block. You prepare pacing and transport.',
                    'food' => 'Oral fluids do not address bradyarrhythmia; patient remains hypotensive.',
                    'orthostatics' => 'Orthostatics delay recognition of arrhythmia causing syncope.',
                ],
            ],
            [
                'prompt' => 'ECG shows complete heart block at 40/min with hypotension. Next action?',
                'options' => [
                    'atropine' => 'Atropine per bradycardia protocol, prepare transcutaneous pacing, transport',
                    'adenosine' => 'Adenosine 6 mg rapid IV push',
                    'discharge' => 'Sign refusal — patient feels fine between pauses',
                    'nitro' => 'Nitroglycerin for chest discomfort',
                ],
                'correct' => 'atropine',
                'outcomes' => [
                    'atropine' => 'Heart rate improves modestly. TCP pads placed. Stable transport to ED.',
                    'adenosine' => 'Patient becomes asystolic briefly — wrong drug for complete block.',
                    'discharge' => 'High-risk syncope patient lost to follow-up against CDC guidance.',
                    'nitro' => 'Worsens hypotension in setting of bradycardia.',
                ],
            ],
        ],
        'explanation' => 'CDC notes syncope with abnormal vitals, injury, or cardiac history warrants emergency evaluation. NHTSA EMS principles require identifying life threats — bradycardia with hypotension requires ACLS bradycardia management per NIH syncope guidance.',
    ],
    [
        'title' => 'Pediatric Respiratory Distress',
        'scenario' => '3-year-old with barky cough, stridor at rest, retractions, SpO₂ 91% on room air. Temp 38.9°C. Parents report worsening over 4 hours.',
        'steps' => [
            [
                'prompt' => 'What is your initial management priority?',
                'options' => [
                    'nebulizer' => 'Keep child calm, administer humidified oxygen, prepare nebulized epinephrine per protocol',
                    'intubate' => 'Immediate RSI without medical management trial',
                    'cpap' => 'Apply CPAP 10 cmH₂O',
                    'antibiotic' => 'Give IM antibiotics for bacterial tracheitis',
                ],
                'correct' => 'nebulizer',
                'outcomes' => [
                    'nebulizer' => 'Stridor softens after nebulized epinephrine. SpO₂ rises to 95% with blow-by oxygen.',
                    'intubate' => 'Unnecessary RSI increases complication risk in croup responsive to medical therapy.',
                    'cpap' => 'CPAP may worsen upper airway obstruction in croup.',
                    'antibiotic' => 'Antibiotics do not address acute croup airway edema.',
                ],
            ],
            [
                'prompt' => 'After treatment, stridor persists at rest and SpO₂ is 89%. Next step?',
                'options' => [
                    'repeat' => 'Repeat nebulized epinephrine per protocol, continue oxygen, rapid transport',
                    'discharge' => 'Discharge with steroid prescription only',
                    'fluids' => 'Large fluid bolus for dehydration',
                    'wait' => 'Observe 2 hours before transport',
                ],
                'correct' => 'repeat',
                'outcomes' => [
                    'repeat' => 'Second treatment partially improves work of breathing. Transport to pediatric-capable ED.',
                    'discharge' => 'Child returns in respiratory failure — CDC pediatric airway guidance violated.',
                    'fluids' => 'Fluids do not relieve upper airway obstruction.',
                    'wait' => 'Delay risks complete obstruction per NIH pediatric emergency guidance.',
                ],
            ],
        ],
        'explanation' => 'CDC and NIH pediatric airway guidance recommend minimizing agitation, oxygen, and nebulized epinephrine for moderate-to-severe croup. Persistent stridor at rest requires repeat treatment and transport to appropriate facility.',
    ],
    [
        'title' => 'Overdose — Opioid with Respiratory Depression',
        'scenario' => 'Unresponsive 28-year-old in alley. RR 6/min, pinpoint pupils, cyanosis, SpO₂ 82%. Empty syringe nearby.',
        'steps' => [
            [
                'prompt' => 'First priority after airway positioning?',
                'options' => [
                    'naloxone' => 'Ventilate with BVM, administer naloxone per protocol, prepare for repeat dosing',
                    'iv_fluids' => 'Two large-bore IVs and 1 L fluid bolus first',
                    'glucose' => 'Oral glucose for altered mental status',
                    'transport_only' => 'Rapid transport without field treatment',
                ],
                'correct' => 'naloxone',
                'outcomes' => [
                    'naloxone' => 'Ventilation improves oxygenation. Naloxone given IM. RR rises to 12/min.',
                    'iv_fluids' => 'Hypoxia continues during IV attempt — patient deteriorates.',
                    'glucose' => 'Cannot swallow; hypoxia worsens.',
                    'transport_only' => 'Prolonged hypoxia causes anoxic injury before ED arrival.',
                ],
            ],
            [
                'prompt' => 'RR is now 14/min but patient remains somnolent. What is next?',
                'options' => [
                    'monitor' => 'Continue ventilation support, repeat naloxone per protocol, transport — watch for re-sedation',
                    'discharge' => 'Wake patient and leave with friend',
                    'narcan_only' => 'Stop all care since naloxone was given once',
                    'charcoal' => 'Activated charcoal administration',
                ],
                'correct' => 'monitor',
                'outcomes' => [
                    'monitor' => 'En route, RR drops to 8/min — you repeat naloxone and maintain ventilation per CDC opioid guidance.',
                    'discharge' => 'Patient re-arrests from long-acting opioid — CDC warns naloxone duration may be shorter than opioid effect.',
                    'narcan_only' => 'Re-sedation common; patient requires monitoring and possible repeat dosing.',
                    'charcoal' => 'Contraindicated with decreased LOC and aspiration risk.',
                ],
            ],
        ],
        'explanation' => 'CDC opioid overdose guidance prioritizes ventilation and naloxone with repeat dosing as needed. NHTSA EMS protocols emphasize monitoring for re-sedation when long-acting opioids are suspected.',
    ],
    [
        'title' => 'Heat Stroke — Critical Hyperthermia',
        'scenario' => 'Marathon runner collapsed. Hot dry skin, GCS 12, temp 40.8°C rectal, HR 128, BP 96/60, minimal sweating.',
        'steps' => [
            [
                'prompt' => 'What is the immediate priority intervention?',
                'options' => [
                    'cool' => 'Aggressive active cooling (ice packs to axilla/groin, evaporative cooling), oxygen, IV access',
                    'fluids_cold' => 'Oral cold fluids only',
                    'wait_shade' => 'Move to shade and observe 30 minutes',
                    'acetaminophen' => 'Acetaminophen 1 g PO for fever',
                ],
                'correct' => 'cool',
                'outcomes' => [
                    'cool' => 'Core temp begins falling. Mental status improves slightly with cooling and oxygen.',
                    'fluids_cold' => 'Cannot protect airway; oral fluids ineffective for heat stroke.',
                    'wait_shade' => 'Passive cooling insufficient — CDC heat illness guidance requires active cooling.',
                    'acetaminophen' => 'Antipyretics do not treat environmental hyperthermia.',
                ],
            ],
            [
                'prompt' => 'During transport, patient seizes briefly. Next action?',
                'options' => [
                    'seizure' => 'Protect airway, continue cooling, benzodiazepine per protocol, rapid transport',
                    'restraint' => 'Apply restraints without sedation',
                    'stop_cool' => 'Stop cooling to focus on seizure only',
                    'discharge' => 'Terminate transport if seizure stops',
                ],
                'correct' => 'seizure',
                'outcomes' => [
                    'seizure' => 'Seizure terminates. Cooling continues. Temp 39.4°C on ED arrival.',
                    'restraint' => 'Increased metabolic heat production worsens hyperthermia.',
                    'stop_cool' => 'Core temp rebounds — NIH heat stroke guidance stresses continuous cooling.',
                    'discharge' => 'Heat stroke carries high mortality without hospital care.',
                ],
            ],
        ],
        'explanation' => 'CDC extreme heat guidance identifies heat stroke as core temp >40°C with CNS dysfunction requiring immediate aggressive cooling. NHTSA EMS protocols prioritize rapid temperature reduction and transport.',
    ],
    [
        'title' => 'Anaphylaxis — Airway Compromise',
        'scenario' => '35-year-old stung by bee: urticaria, wheezing, stridor, BP 92/58, SpO₂ 90%. EpiPen used 8 minutes ago without improvement.',
        'steps' => [
            [
                'prompt' => 'What is your next best action?',
                'options' => [
                    'epi' => 'IM epinephrine repeat per protocol, high-flow oxygen, IV access, prepare advanced airway',
                    'antihistamine' => 'Diphenhydramine IV only',
                    'steroid' => 'Methylprednisolone before epinephrine',
                    'transport_only' => 'Load without further treatment',
                ],
                'correct' => 'epi',
                'outcomes' => [
                    'epi' => 'Second IM epinephrine improves stridor. SpO₂ rises with nebulized albuterol.',
                    'antihistamine' => 'Antihistamines are adjuncts — do not replace epinephrine in anaphylaxis.',
                    'steroid' => 'Steroids have delayed onset; airway still compromised.',
                    'transport_only' => 'Patient progresses to complete obstruction en route.',
                ],
            ],
            [
                'prompt' => 'Stridor persists despite second epinephrine. Next step?',
                'options' => [
                    'airway' => 'Prepare RSI/surgical airway, nebulized epinephrine if protocol, fluids, rapid transport',
                    'wait' => 'Wait 30 minutes for steroids to work',
                    'cpap' => 'CPAP for stridor',
                    'po' => 'Oral antihistamine and observe',
                ],
                'correct' => 'airway',
                'outcomes' => [
                    'airway' => 'RSI successful. Ventilation adequate. Stable transport per FDA anaphylaxis adjunct guidance.',
                    'wait' => 'Progressive laryngeal edema causes arrest.',
                    'cpap' => 'May worsen upper airway obstruction.',
                    'po' => 'Cannot swallow safely with stridor.',
                ],
            ],
        ],
        'explanation' => 'FDA epinephrine labeling and CDC anaphylaxis guidance support repeat IM epinephrine for persistent symptoms. Progressive stridor requires preparation for definitive airway per NHTSA ALS protocols.',
    ],
    [
        'title' => 'DKA — Diabetic Emergency',
        'scenario' => '19-year-old type 1 diabetic: polyuria, vomiting, Kussmaul respirations, fruity breath, glucose 486 mg/dL, BP 102/64, HR 118.',
        'steps' => [
            [
                'prompt' => 'What is the priority field assessment and action?',
                'options' => [
                    'support' => 'ABC assessment, IV access, fluid bolus per protocol, transport — avoid insulin in field unless protocol allows',
                    'insulin' => 'Regular insulin 10 units IV push immediately',
                    'oral' => 'Encourage large oral fluid intake',
                    'discharge' => 'Reassure and refer to primary care',
                ],
                'correct' => 'support',
                'outcomes' => [
                    'support' => 'IV fluids initiated. Patient remains alert. Transport to ED for insulin and electrolyte management.',
                    'insulin' => 'Field insulin without labs risks hypokalemia and cerebral edema per NIH DKA guidance.',
                    'oral' => 'Vomiting prevents oral hydration; aspiration risk.',
                    'discharge' => 'DKA progresses to shock and altered mental status.',
                ],
            ],
            [
                'prompt' => 'En route, GCS drops to 13 and vomiting continues. Next action?',
                'options' => [
                    'airway' => 'Position airway, suction, consider advanced airway if unable to protect, continue fluids, expedite transport',
                    'food' => 'Give oral glucose',
                    'kcl' => 'Potassium bolus in field',
                    'stop_fluids' => 'Stop fluids due to vomiting',
                ],
                'correct' => 'airway',
                'outcomes' => [
                    'airway' => 'Airway protected with positioning and suction. GCS stable until ED.',
                    'food' => 'Wrong intervention — hyperglycemia, not hypoglycemia.',
                    'kcl' => 'Potassium management requires lab values unavailable in field per CDC diabetes guidance.',
                    'stop_fluids' => 'Dehydration worsens DKA shock.',
                ],
            ],
        ],
        'explanation' => 'NIH and CDC diabetes emergency guidance emphasizes fluid resuscitation and transport for DKA. Field insulin is generally deferred to hospital management with electrolyte monitoring.',
    ],
    [
        'title' => 'GI Bleed — Hemodynamic Instability',
        'scenario' => '62-year-old with hematemesis. Pale, diaphoretic, BP 88/50, HR 118, Hgb unknown. History of NSAID use and alcohol.',
        'steps' => [
            [
                'prompt' => 'What is your initial management priority?',
                'options' => [
                    'shock' => 'Two large-bore IVs, cautious fluid resuscitation, oxygen, monitor, rapid transport',
                    'npo_wait' => 'Nothing by mouth and wait for BP to normalize spontaneously',
                    'ng' => 'Insert NG tube and lavage in field',
                    'po_fluids' => 'Oral electrolyte solution',
                ],
                'correct' => 'shock',
                'outcomes' => [
                    'shock' => 'IV access established. BP improves to 96/58 with fluid. Transport initiated.',
                    'npo_wait' => 'Hypotension worsens — ongoing hemorrhage requires resuscitation.',
                    'ng' => 'NG lavage not standard prehospital care and may provoke vomiting.',
                    'po_fluids' => 'Aspiration risk with hematemesis and altered perfusion.',
                ],
            ],
            [
                'prompt' => 'BP remains 90/60 after 500 mL fluid. Next step?',
                'options' => [
                    'pressor' => 'Continue fluids cautiously, consider push-dose pressor per protocol, type and cross notify, expedite transport',
                    'limit_iv' => 'Stop all IV fluids to prevent dilution',
                    'oral' => 'Oral proton pump inhibitor',
                    'discharge' => 'Patient stable enough for outpatient GI referral',
                ],
                'correct' => 'pressor',
                'outcomes' => [
                    'pressor' => 'MAP improves with cautious resuscitation. ED prepares for transfusion and endoscopy.',
                    'limit_iv' => 'Under-resuscitation worsens shock per NIH bleeding guidance.',
                    'oral' => 'Cannot address hemorrhagic shock.',
                    'discharge' => 'Upper GI bleed with shock requires hospital care.',
                ],
            ],
        ],
        'explanation' => 'NIH bleeding and shock guidance supports large-bore IV access and cautious crystalloid for hemodynamic instability. NHTSA EMS assessment prioritizes perfusion and rapid transport for GI hemorrhage.',
    ],
    [
        'title' => 'Psychiatric Emergency — Capacity and Safety',
        'scenario' => 'Agitated male threatening self-harm with knife. Scene secured by police. Patient calm when you arrive, denies SI now, wants to stay home.',
        'steps' => [
            [
                'prompt' => 'What is your first clinical priority?',
                'options' => [
                    'assess' => 'Complete medical assessment for organic causes, evaluate capacity and imminent risk with law enforcement',
                    'leave' => 'Leave immediately since patient denies SI',
                    'restrain' => 'Apply restraints without assessment',
                    'sedate' => 'IM sedation before any conversation',
                ],
                'correct' => 'assess',
                'outcomes' => [
                    'assess' => 'You identify recent overdose attempt, tachycardia, and inconsistent story. Medical clearance needed.',
                    'leave' => 'Patient re-attempts self-harm after crew departure.',
                    'restraint' => 'Restraint without assessment may escalate agitation.',
                    'sedate' => 'Sedation masks underlying medical emergency.',
                ],
            ],
            [
                'prompt' => 'Patient has acetaminophen bottles empty nearby. Next action?',
                'options' => [
                    'toxidrome' => 'Treat as toxic ingestion, IV access, transport for NAC evaluation — involuntary hold per local protocol if needed',
                    'psych_only' => 'Psychiatric transport only without medical workup',
                    'discharge' => 'Allow police to release patient home',
                    'charcoal' => 'Refuse transport if patient declines charcoal',
                ],
                'correct' => 'toxidrome',
                'outcomes' => [
                    'toxidrome' => 'Transport for acetaminophen level and NAC protocol. Time-critical antidote window preserved.',
                    'psych_only' => 'Misses hepatotoxic overdose requiring medical treatment per FDA acetaminophen guidance.',
                    'discharge' => 'Delayed antidote risks liver failure.',
                    'charcoal' => 'Charcoal timing and consent issues do not replace hospital evaluation.',
                ],
            ],
        ],
        'explanation' => 'NHTSA EMS and CDC mental health crisis guidance require medical assessment for coexisting medical or toxicologic emergencies. Acetaminophen overdose requires hospital evaluation within antidote window per NIH poison center recommendations.',
    ],
]);
