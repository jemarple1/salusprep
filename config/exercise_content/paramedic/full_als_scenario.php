<?php

require_once __DIR__.'/helpers.php';

return paramedic_levels([
    [
        'title' => 'Chest Pain — Dispatch to PCI Handoff',
        'scenario' => 'Dispatch: 62-year-old male, crushing chest pain, diaphoretic, 15 minutes. ETA to scene 4 minutes. PCI center 18 minutes from scene.',
        'steps' => [
            [
                'prompt' => 'On scene — patient alert, pale, BP 148/92, HR 98, SpO₂ 93%. First action?',
                'options' => [
                    'load' => 'Load immediately without 12-lead',
                    'assess' => 'Oxygen, 12-lead ECG, IV, aspirin per protocol while preparing transport',
                    'nitro' => 'Nitroglycerin before ECG',
                    'morphine' => 'Morphine 4 mg IV before assessment',
                ],
                'correct' => 'assess',
                'outcomes' => [
                    'load' => 'No field 12-lead captured — hospital delay in STEMI recognition.',
                    'assess' => '12-lead shows anterior STEMI. Aspirin given. STEMI alert activated from scene.',
                    'nitro' => 'Pain relief without ischemia documentation — may mask evolving shock.',
                    'morphine' => 'Pain treated but reperfusion pathway not initiated promptly.',
                ],
            ],
            [
                'prompt' => 'En route — BP drops to 92/58, crackles developing. Next?',
                'options' => [
                    'cardiogenic' => 'Cautious fluids, notify PCI team of cardiogenic shock, norepinephrine per protocol, repeat 12-lead',
                    'nitro_drip' => 'Nitroglycerin drip for all STEMI',
                    'rapid_fluids' => '1 L rapid bolus without reassessment',
                    'stop' => 'Pull over and terminate transport',
                ],
                'correct' => 'cardiogenic',
                'outcomes' => [
                    'cardiogenic' => 'Cautious resuscitation. PCI team prepares for IABP/impella. Patient arrives with MAP supported.',
                    'nitro_drip' => 'Worsens hypotension in cardiogenic shock per NHLBI guidance.',
                    'rapid_fluids' => 'Pulmonary edema worsens with aggressive fluids.',
                    'stop' => 'Delays reperfusion — door-to-balloon time critical.',
                ],
            ],
            [
                'prompt' => 'Hospital arrival — handoff element most critical?',
                'options' => [
                    'handoff' => 'SBAR: STEMI time of onset, 12-leads, aspirin/time, shocks/pressors, current vitals, IV access',
                    'minimal' => 'State "heart attack" only and leave',
                    'paper' => 'Leave paper run sheet without verbal report',
                    'vitals_only' => 'Report only last BP',
                ],
                'correct' => 'handoff',
                'outcomes' => [
                    'handoff' => 'Cath lab activated on arrival. Door-to-balloon within target. Crew debriefs call.',
                    'minimal' => 'Critical cardiogenic shock details omitted — delays intervention.',
                    'paper' => 'Verbal handoff required per NHTSA EMS patient safety and AHA STEMI systems.',
                    'vitals_only' => 'Missing ECG and medication timeline delays care.',
                ],
            ],
        ],
        'explanation' => 'NHTSA EMS assessment, NIH NHLBI STEMI systems, and AHA handoff standards require field 12-lead, STEMI alert, shock recognition, and structured SBAR handoff to PCI team.',
    ],
    [
        'title' => 'Cardiac Arrest — VF to ROSC',
        'scenario' => 'Dispatch: unresponsive male, bystander CPR in progress. AED shock delivered x1 before your arrival.',
        'steps' => [
            [
                'prompt' => 'Assume care — monitor shows VF. First action?',
                'options' => [
                    'shock' => 'Resume CPR, charge, defibrillate, immediate CPR x2 min per ACLS',
                    'epi' => 'Epinephrine before second shock',
                    'intubate' => 'RSI before any further shocks',
                    'pause' => 'Pause 5 minutes to assess prognosis',
                ],
                'correct' => 'shock',
                'outcomes' => [
                    'shock' => 'Shock delivered. CPR resumed. Team rotates compressors per AHA quality metrics.',
                    'epi' => 'Epinephrine before second shock — incorrect ACLS timing.',
                    'intubate' => 'Airway can wait — compressions and defibrillation priority per CDC chain of survival.',
                    'pause' => 'Continuous CPR except shock/analysis pauses.',
                ],
            ],
            [
                'prompt' => 'After 3 shocks and epinephrine, still VF. Next?',
                'options' => [
                    'amio' => 'Amiodarone 300 mg IV, continue CPR/shocks, consider reversible causes',
                    'stop' => 'Terminate resuscitation in field',
                    'atropine' => 'Atropine 3 mg for VF',
                    'sync' => 'Synchronized cardioversion for VF',
                ],
                'correct' => 'amio',
                'outcomes' => [
                    'amio' => 'Fourth shock converts to organized rhythm. Pulse check shows ROSC.',
                    'stop' => 'Premature termination — reversible causes may exist.',
                    'atropine' => 'Not indicated in VF arrest.',
                    'sync' => 'VF requires unsynchronized shock.',
                ],
            ],
            [
                'prompt' => 'ROSC — GCS 6, BP 88/50, EtCO₂ 38. Transport priority?',
                'options' => [
                    'post_rosc' => 'Avoid hyperventilation, titrate O₂, vasopressor/fluids, 12-lead, hypothermia-capable center handoff',
                    'hypervent' => 'Ventilate to EtCO₂ 25',
                    'extubate' => 'Remove advanced airway immediately',
                    'refuse' => 'Family refuses transport after ROSC',
                ],
                'correct' => 'post_rosc',
                'outcomes' => [
                    'post_rosc' => 'Stable transport. ED receives full arrest timeline and post-ROSC vitals.',
                    'hypervent' => 'Cerebral perfusion worsens with hypocapnia per AHA post-arrest care.',
                    'extubate' => 'Airway protection needed with GCS 6.',
                    'refuse' => 'ROSC patients require hospital evaluation — capacity assessment per protocol.',
                ],
            ],
            [
                'prompt' => 'ED handoff — essential information?',
                'options' => [
                    'arrest_report' => 'Downtime estimate, shocks/drugs/times, EtCO₂ trends, suspected cause, ROSC time, current support',
                    'brief' => '"We got a pulse back" only',
                    'blame' => 'Focus on bystander CPR quality criticism',
                    'skip' => 'Leave during compressions if re-arrest',
                ],
                'correct' => 'arrest_report',
                'outcomes' => [
                    'arrest_report' => 'ED initiates post-arrest bundle immediately. Cath lab considered if STEMI.',
                    'brief' => 'Missing downtime and intervention timeline delays targeted therapy.',
                    'blame' => 'Non- constructive — focus on patient data per NHTSA QA culture.',
                    'skip' => 'Handoff required unless ongoing critical intervention prevents it.',
                ],
            ],
        ],
        'explanation' => 'AHA ACLS and CDC resuscitation guidance: shock VF promptly, amiodarone for refractory VF, post-ROSC bundle with structured arrest report handoff to receiving facility.',
    ],
    [
        'title' => 'Multi-System Trauma — MVC',
        'scenario' => 'Dispatch: high-speed MVC, entrapped driver, priority 1. Helicopter unavailable. 22 minutes to Level I trauma center.',
        'steps' => [
            [
                'prompt' => 'Reach patient — GCS 11, RR 28, SpO₂ 88%, BP 104/70, obvious pelvis deformity. First priority?',
                'options' => [
                    'airway' => 'Airway/oxygen, control external hemorrhage, pelvic binder, IV/IO while coordinating extrication',
                    'extricate' => 'Immediate extrication without assessment',
                    'spine_only' => 'C-collar only — defer all other care until extricated',
                    'pain_only' => 'Pain control before airway',
                ],
                'correct' => 'airway',
                'outcomes' => [
                    'airway' => 'SpO₂ improved with oxygen and positioning. Pelvic binder applied. Extrication planned.',
                    'extricate' => 'Hypoxia worsens during uncoordinated extrication.',
                    'spine_only' => 'Life threats (hypoxia, hemorrhage) take priority per NHTSA trauma assessment.',
                    'pain_only' => 'Airway and bleeding precede analgesia in unstable trauma.',
                ],
            ],
            [
                'prompt' => 'Extricated — BP 82/50, HR 130. Next?',
                'options' => [
                    'hemorrhage' => 'TXA if protocol, blood products if available, permissive hypotension if penetrating, rapid transport',
                    'fluids_2l' => '2 L bolus before transport regardless of mechanism',
                    'scene' => 'Remain on scene for complete secondary survey 20 minutes',
                    'oral' => 'Oral fluids for shock',
                ],
                'correct' => 'hemorrhage',
                'outcomes' => [
                    'hemorrhage' => 'Resuscitation en route. Trauma alert with ETA. BP 92/58 on arrival.',
                    'fluids_2l' => 'May dilute clotting factors — balanced resuscitation per NIH trauma guidance.',
                    'scene' => 'Golden hour violated — CDC field triage emphasizes transport.',
                    'oral' => 'Contraindicated with decreased LOC.',
                ],
            ],
            [
                'prompt' => 'Trauma center handoff — priority data?',
                'options' => [
                    'trauma_sbar' => 'Mechanism, GCS trend, pelvis binder time, fluids/products, TXA, vitals q5 min, ETA notifications',
                    'gcs' => 'GCS only',
                    'mechanism' => 'Mechanism only without vitals trend',
                    'none' => 'No handoff — patient belongs to trauma team now',
                ],
                'correct' => 'trauma_sbar',
                'outcomes' => [
                    'trauma_sbar' => 'Trauma team activates massive transfusion protocol on arrival.',
                    'gcs' => 'Missing hemodynamic trend and interventions given.',
                    'mechanism' => 'Vital sign trends essential for ongoing resuscitation.',
                    'none' => 'NHTSA requires complete transfer of care documentation and verbal report.',
                ],
            ],
        ],
        'explanation' => 'CDC field triage and NIH trauma guidelines prioritize airway, hemorrhage control, pelvic stabilization, and balanced resuscitation with structured trauma handoff.',
    ],
    [
        'title' => 'Stroke Alert — LVO Suspicion',
        'scenario' => 'Dispatch: 58-year-old sudden left hemiplegia, aphasia, last known well 35 minutes ago. Comprehensive stroke center 15 minutes away.',
        'steps' => [
            [
                'prompt' => 'Scene assessment — FAST positive, glucose 112, BP 188/102. First action?',
                'options' => [
                    'stroke' => 'Stroke alert notification, last known well time, glucose, BP management per protocol, rapid transport',
                    'delay' => 'Wait for family to arrive before transport',
                    'tpa' => 'Administer IV tPA in ambulance',
                    'oral' => 'Aspirin 325 mg before BP assessment',
                ],
                'correct' => 'stroke',
                'outcomes' => [
                    'stroke' => 'Stroke alert called with ETA. BP treated per protocol without delaying transport.',
                    'delay' => 'Time window expires — NIH "time is brain" guidance violated.',
                    'tpa' => 'tPA not standard prehospital scope — hospital decision after imaging.',
                    'oral' => 'Aspirin timing per protocol after hemorrhage excluded at hospital.',
                ],
            ],
            [
                'prompt' => 'En route — patient vomits, GCS drops to 13. Next?',
                'options' => [
                    'airway' => 'Position airway, suction, O₂, consider BP adjustment, continue stroke alert with updated neuro status',
                    'turn_back' => 'Return to scene',
                    'hypervent' => 'Hyperventilate to reduce ICP',
                    'delay_ed' => 'Stop en route for repeat full neuro exam 30 minutes',
                ],
                'correct' => 'airway',
                'outcomes' => [
                    'airway' => 'Airway protected. Updated GCS communicated to stroke team for thrombectomy readiness.',
                    'turn_back' => 'Delays definitive care.',
                    'hypervent' => 'Routine hyperventilation not recommended in stroke per NIH stroke guidelines.',
                    'delay_ed' => 'Transport continues — pre-arrival updates critical.',
                ],
            ],
            [
                'prompt' => 'Stroke center arrival — handoff essentials?',
                'options' => [
                    'stroke_handoff' => 'Last known well, deficit description, glucose, vitals, medications given, stroke alert time, trend',
                    'fast' => 'Say "FAST positive" only',
                    'family' => 'Defer all report to family',
                    'no_time' => 'Omit last known well — hospital will figure it out',
                ],
                'correct' => 'stroke_handoff',
                'outcomes' => [
                    'stroke_handoff' => 'Team routes to CT and thrombectomy evaluation immediately.',
                    'fast' => 'Missing last known well may exclude reperfusion therapy.',
                    'family' => 'Clinical handoff is EMS responsibility per CDC stroke systems of care.',
                    'no_time' => 'Last known well is critical eligibility determinant per NIH stroke scale guidance.',
                ],
            ],
        ],
        'explanation' => 'CDC stroke systems of care and NIH stroke scale guidance require early notification, last known well documentation, and BP management without transport delay.',
    ],
    [
        'title' => 'Pediatric Anaphylaxis — School',
        'scenario' => 'Dispatch: 7-year-old peanut allergy, stridor, school nurse gave EpiPen 6 minutes ago. BP 78/40, HR 140.',
        'steps' => [
            [
                'prompt' => 'On scene — stridor persists, hives improving. First action?',
                'options' => [
                    'repeat_epi' => 'Repeat IM epinephrine, O₂, IV/IO, albuterol, rapid transport',
                    'antihistamine' => 'Diphenhydramine IV only',
                    'wait' => 'Wait 15 minutes for first EpiPen',
                    'po' => 'Oral epinephrine',
                ],
                'correct' => 'repeat_epi',
                'outcomes' => [
                    'repeat_epi' => 'Second epinephrine improves stridor. IV access established. Transport initiated.',
                    'antihistamine' => 'Adjunct only — does not replace epinephrine in anaphylactic shock.',
                    'wait' => 'Airway may close — FDA supports repeat epinephrine at 5–15 minutes.',
                    'po' => 'Cannot absorb in shock; aspiration risk.',
                ],
            ],
            [
                'prompt' => 'En route — BP 82/45 after fluids. Next?',
                'options' => [
                    'epi_infusion' => 'Epinephrine infusion per protocol if available, continue albuterol, prepare RSI if stridor worsens',
                    'discharge' => 'Improvement means cancel transport',
                    'steroid_only' => 'Steroids alone for shock',
                    'cpap' => 'CPAP for stridor',
                ],
                'correct' => 'epi_infusion',
                'outcomes' => [
                    'epi_infusion' => 'BP improves. ED continues observation for biphasic reaction per CDC anaphylaxis guidance.',
                    'discharge' => 'Biphasic anaphylaxis risk requires ED observation.',
                    'steroid_only' => 'Steroids have delayed onset — not for acute shock.',
                    'cpap' => 'May worsen upper airway edema.',
                ],
            ],
            [
                'prompt' => 'Pediatric ED handoff?',
                'options' => [
                    'peds_handoff' => 'Allergen, EpiPen times/doses, epinephrine given, fluids, airway status, biphasic risk discussion',
                    'brief' => 'Allergic reaction — bye',
                    'no_times' => 'Omit epinephrine timing',
                    'parent_only' => 'Parent tells story — no EMS report',
                ],
                'correct' => 'peds_handoff',
                'outcomes' => [
                    'peds_handoff' => 'ED continues monitoring 4–8 hours per NIH pediatric anaphylaxis observation guidelines.',
                    'brief' => 'Missing epinephrine timeline affects further dosing decisions.',
                    'no_times' => 'Timing critical for repeat dosing and observation duration.',
                    'parent_only' => 'EMS must provide medical handoff per NHTSA pediatric readiness standards.',
                ],
            ],
        ],
        'explanation' => 'FDA epinephrine labeling, CDC anaphylaxis guidance, and NHTSA pediatric EMS standards require repeat epinephrine, transport, and detailed handoff including all epinephrine doses and times.',
    ],
    [
        'title' => 'Obstetric Emergency — Precipitous Delivery',
        'scenario' => 'Dispatch: 38 weeks pregnant, active delivery, contractions 2 minutes apart, no prenatal complications known.',
        'steps' => [
            [
                'prompt' => 'Arrive — crowning visible, urge to push. Action?',
                'options' => [
                    'deliver' => 'Prepare for delivery, call OB backup, monitor mother and FHT, deliver with controlled pushing',
                    'transport' => 'Transport with head crowning — delay delivery',
                    'fundal' => 'Fundal pressure before head delivers',
                    'supine' => 'Force supine flat position only',
                ],
                'correct' => 'deliver',
                'outcomes' => [
                    'deliver' => 'Controlled delivery of head and shoulders. Baby delivered with good tone.',
                    'transport' => 'Head may deliver en route uncontrolled — prepare for delivery in ambulance.',
                    'fundal' => 'Fundal pressure not indicated before shoulder delivery.',
                    'supine' => 'Left tilt reduces aortocaval compression per NIH obstetric guidance.',
                ],
            ],
            [
                'prompt' => 'Baby apneic, HR 70/min. Next?',
                'options' => [
                    'nrp' => 'Dry, warm, stimulate, begin PPV; compressions if HR <60 after 30 sec effective ventilation',
                    'intubate' => 'Immediate cricothyrotomy on newborn',
                    'shake' => 'Shake vigorously',
                    'maternal' => 'Focus on placenta before newborn',
                ],
                'correct' => 'nrp',
                'outcomes' => [
                    'nrp' => 'PPV establishes respirations. HR 140/min. APGAR improving.',
                    'intubate' => 'PPV with BVM first per NRP.',
                    'shake' => 'Not NRP recommended.',
                    'maternal' => 'Newborn resuscitation concurrent with maternal third stage management.',
                ],
            ],
            [
                'prompt' => 'Mother bleeding moderately, uterus soft. Next?',
                'options' => [
                    'pph' => 'Fundal massage, oxytocin per protocol, IV access, transport both patients',
                    'ignore' => 'Ignore bleeding — normal after all deliveries',
                    'manual' => 'Field hysterectomy',
                    'separate' => 'Transport baby only',
                ],
                'correct' => 'pph',
                'outcomes' => [
                    'pph' => 'Bleeding controlled with massage and oxytocin. Both transported to OB emergency.',
                    'ignore' => 'Atonic uterus can cause massive PPH per CDC maternal mortality reviews.',
                    'manual' => 'Not field procedure.',
                    'separate' => 'Mother requires hemorrhage management.',
                ],
            ],
            [
                'prompt' => 'OB ED handoff includes?',
                'options' => [
                    'ob_handoff' => 'Gestation, delivery time, NRP interventions, APGAR, PPH treatments, estimated blood loss, placenta status',
                    'baby_only' => 'Report only on baby',
                    'minimal' => 'Normal delivery — no details needed',
                    'delay' => 'Written report tomorrow',
                ],
                'correct' => 'ob_handoff',
                'outcomes' => [
                    'ob_handoff' => 'OB team assesses uterine tone and neonatal transition simultaneously.',
                    'baby_only' => 'Maternal PPH details critical for ongoing care.',
                    'minimal' => 'PPH and resuscitation require full report per NHTSA obstetric EMS protocols.',
                    'delay' => 'Immediate verbal handoff required.',
                ],
            ],
        ],
        'explanation' => 'NIH obstetric and NRP guidance for field delivery: controlled delivery, newborn ventilation sequence, PPH prevention with uterotonics, and comprehensive dual-patient handoff.',
    ],
    [
        'title' => 'Sepsis — Nursing Home Transfer',
        'scenario' => 'Dispatch: 89-year-old SNF patient, fever, altered, hypotensive per nurse. Full code. 25 minutes to ED with sepsis protocol.',
        'steps' => [
            [
                'prompt' => 'Assessment — temp 39.8°C, BP 86/48, HR 122, RR 26, SpO₂ 91%, indwelling catheter. First action?',
                'options' => [
                    'sepsis' => 'Sepsis alert, O₂, IV access, 500 mL fluid bolus, repeat vitals, rapid transport',
                    'wait' => 'Obtain complete medical records before leaving',
                    'po' => 'Oral antibiotics from SNF supply',
                    'dnr' => 'Assume DNR without documentation',
                ],
                'correct' => 'sepsis',
                'outcomes' => [
                    'sepsis' => 'Fluids and oxygen initiated. Sepsis alert called with ETA. Lactate pending at ED.',
                    'wait' => 'Delays sepsis bundle — CDC hour-1 emphasis on early fluids and antibiotics.',
                    'po' => 'Cannot address shock; IV antibiotics at hospital.',
                    'dnr' => 'Nurse stated full code — treat accordingly.',
                ],
            ],
            [
                'prompt' => 'After fluid, BP 90/52, patient more confused. Next?',
                'options' => [
                    'pressor' => 'Second fluid bolus cautiously, norepinephrine if protocol, continue sepsis alert, document trend',
                    'stop_fluids' => 'Stop all fluids — too much already',
                    'discharge' => 'Return to SNF with oral antibiotics script',
                    'cool' => 'Active cooling for fever before resuscitation',
                ],
                'correct' => 'pressor',
                'outcomes' => [
                    'pressor' => 'MAP improves with vasopressor infusion. ED continues sepsis bundle.',
                    'stop_fluids' => 'Under-resuscitation worsens organ perfusion per NIH Surviving Sepsis.',
                    'discharge' => 'Severe sepsis requires hospital care.',
                    'cool' => 'Antipyretics at hospital — perfusion priority in shock.',
                ],
            ],
            [
                'prompt' => 'ED sepsis handoff?',
                'options' => [
                    'sepsis_sbar' => 'Source suspicion (UTI), vitals trend, fluids/volumes, pressors, antibiotics not given, code status, baseline cognition',
                    'fever' => 'Patient has fever',
                    'vitals' => 'Last BP only',
                    'none' => 'SNF paperwork sufficient',
                ],
                'correct' => 'sepsis_sbar',
                'outcomes' => [
                    'sepsis_sbar' => 'ED administers broad-spectrum antibiotics within hour-1 target.',
                    'fever' => 'Missing perfusion and fluid response data.',
                    'vitals' => 'Trend more valuable than single reading per CDC sepsis quality measures.',
                    'none' => 'Verbal handoff mandatory per NHTSA.',
                ],
            ],
        ],
        'explanation' => 'CDC sepsis hour-1 bundle and NIH Surviving Sepsis Campaign emphasize early recognition, fluids, vasopressors when needed, and pre-arrival notification with clinical trend handoff.',
    ],
    [
        'title' => 'Airway Burn — Structure Fire',
        'scenario' => 'Dispatch: house fire victim, hoarseness, facial burns, removed by fire department. SpO₂ 87% on scene. Burn center 35 minutes.',
        'steps' => [
            [
                'prompt' => 'Primary survey — stridor at rest, soot in nares, RR 32. Priority?',
                'options' => [
                    'early_airway' => '100% O₂, early RSI consideration, IV, burn size estimate, burn center alert',
                    'cpap' => 'CPAP for stridor',
                    'delay_airway' => 'Defer airway until ED',
                    'topical' => 'Topical antibiotic ointment on face first',
                ],
                'correct' => 'early_airway',
                'outcomes' => [
                    'early_airway' => 'RSI successful before edema progresses. Ventilator with lung-protective settings.',
                    'cpap' => 'Contraindicated with upper airway edema and stridor.',
                    'delay_airway' => 'Progressive edema may make intubation impossible per CDC inhalation injury guidance.',
                    'topical' => 'Airway threat precedes burn dressing.',
                ],
            ],
            [
                'prompt' => 'En route — CO exposure suspected. Additional care?',
                'options' => [
                    'co' => 'High-flow 100% O₂, monitor for dysrhythmia, consider hyperbaric consultation at burn center',
                    'room_air' => 'Wean to room air quickly',
                    'hypervent' => 'Hyperventilate to wash out CO faster',
                    'ignore' => 'CO irrelevant if SpO₂ normal on pulse ox',
                ],
                'correct' => 'co',
                'outcomes' => [
                    'co' => '100% O₂ continued. Carboxyhemoglobin level at hospital guides hyperbaric need per NIH CO poisoning guidance.',
                    'room_air' => 'Pulse ox falsely normal with CO — high-flow O₂ required.',
                    'hypervent' => 'Does not replace high-flow O₂ for CO elimination.',
                    'ignore' => 'CO poisoning common in structure fires per CDC.',
                ],
            ],
            [
                'prompt' => 'Burn center handoff?',
                'options' => [
                    'burn_sbar' => 'Inhalation signs, intubation time, TBSA estimate, fluids started, CO concern, fire exposure duration',
                    'burns' => 'Patient has burns — that is all',
                    'airway_only' => 'Airway only without burn details',
                    'photos' => 'Show phone photos instead of verbal report',
                ],
                'correct' => 'burn_sbar',
                'outcomes' => [
                    'burn_sbar' => 'Burn team prepares ICU bed and escharotomy equipment if needed.',
                    'burns' => 'Inhalation injury and CO risk omitted.',
                    'airway_only' => 'TBSA and fluid resuscitation plan needed.',
                    'photos' => 'Supplement verbal handoff — not replacement per NHTSA documentation standards.',
                ],
            ],
        ],
        'explanation' => 'CDC burn and inhalation injury guidance: early intubation when stridor or significant facial burns, 100% O₂ for CO, burn center notification with TBSA and airway status handoff.',
    ],
    [
        'title' => 'Psychiatric Emergency — Medical Clearance',
        'scenario' => 'Dispatch: suicidal patient, police on scene, calm now, wants voluntary psych hold. Empty pill bottles nearby.',
        'steps' => [
            [
                'prompt' => 'Assessment — acetaminophen and diphenhydramine bottles empty, asymptomatic, vitals normal. Action?',
                'options' => [
                    'toxidrome' => 'Treat as potential overdose, IV access, transport for acetaminophen level and NAC protocol evaluation',
                    'psych_only' => 'Psychiatric transport without medical evaluation',
                    'release' => 'Release to police',
                    'charcoal' => 'Force charcoal in field and discharge',
                ],
                'correct' => 'toxidrome',
                'outcomes' => [
                    'toxidrome' => 'Transport for labs. Acetaminophen level elevated — NAC started within window.',
                    'psych_only' => 'Hepatotoxicity may develop silently per FDA acetaminophen guidance.',
                    'release' => 'Missed overdose treatment window.',
                    'charcoal' => 'Timing and consent issues — hospital manages antidote.',
                ],
            ],
            [
                'prompt' => 'En route — patient vomits, mild RUQ tenderness developing. Next?',
                'options' => [
                    'support' => 'Anti-emetic per protocol, continue transport, notify ED of evolving acetaminophen toxicity signs',
                    'stop' => 'Terminate transport — patient faking',
                    'psych' => 'Divert to psych facility bypassing medical ED',
                    'restraint' => 'Restrain without medical care',
                ],
                'correct' => 'support',
                'outcomes' => [
                    'support' => 'ED continues NAC. Psychiatric evaluation after medical clearance per CDC behavioral health integration.',
                    'stop' => 'Early toxicity signs require continued medical transport.',
                    'psych' => 'Medical clearance required before psych admission for overdose.',
                    'restraint' => 'Does not treat hepatotoxicity.',
                ],
            ],
            [
                'prompt' => 'ED handoff for dual diagnosis case?',
                'options' => [
                    'dual_sbar' => 'Substances ingested, estimated time, empty bottles, vitals trend, antiemetic given, psych hold status, capacity',
                    'psych' => 'Suicidal — psych problem only',
                    'no_substance' => 'Omit pill information',
                    'police' => 'Police give report — EMS silent',
                ],
                'correct' => 'dual_sbar',
                'outcomes' => [
                    'dual_sbar' => 'Medical and psychiatric teams coordinate NAC and safety assessment.',
                    'psych' => 'Toxicology omitted — delays antidote.',
                    'no_substance' => 'Substance identification critical for NAC timing.',
                    'police' => 'EMS clinical handoff required per NHTSA.',
                ],
            ],
        ],
        'explanation' => 'NHTSA EMS and NIH poison center guidance: psychiatric emergencies require medical clearance for overdose. Acetaminophen toxicity needs level and NAC within FDA-recommended treatment window.',
    ],
    [
        'title' => 'Interfacility Critical Care Transfer',
        'scenario' => 'Dispatch: interfacility transfer, rural ED STEMI post fibrinolysis, residual ST elevation, BP 98/62, 45-minute transport to PCI center.',
        'steps' => [
            [
                'prompt' => 'Assume care from sending RN — verify lines, infusions, 12-lead. First action?',
                'options' => [
                    'verify' => 'Verify heparin infusion, repeat 12-lead, monitor, confirm PCI team aware of ETA and fibrinolysis time',
                    'disconnect' => 'Stop all infusions for transport simplicity',
                    'speed' => 'Transport without patient assessment',
                    'new_orders' => 'Change all orders without medical control',
                ],
                'correct' => 'verify',
                'outcomes' => [
                    'verify' => 'Care continuum maintained. PCI team prepares for rescue angiography.',
                    'disconnect' => 'Heparin interruption increases thrombotic risk per AHA fibrinolysis guidance.',
                    'speed' => 'Interfacility transfer requires full assessment per NHTSA critical care transport standards.',
                    'new_orders' => 'Requires medical control — do not unilaterally change sending orders.',
                ],
            ],
            [
                'prompt' => 'En route — chest pain 6/10, new runs of VT 5 beats. Next?',
                'options' => [
                    'monitor' => 'Continuous monitoring, antiarrhythmic per protocol/medical control, repeat 12-lead, notify PCI team',
                    'ignore' => 'NSR now — ignore VT runs',
                    'stop' => 'Return to sending facility',
                    'discharge' => 'Patient stable — terminate transfer',
                ],
                'correct' => 'monitor',
                'outcomes' => [
                    'monitor' => 'Amiodarone per protocol. Pain persists — rescue PCI urgency communicated.',
                    'ignore' => 'VT in post-fibrinolysis STEMI is high-risk per NHLBI guidance.',
                    'stop' => 'Delays definitive PCI.',
                    'discharge' => 'Cannot terminate against medical necessity and orders.',
                ],
            ],
            [
                'prompt' => 'PCI center handoff?',
                'options' => [
                    'transfer_sbar' => 'Fibrinolytic agent/time, heparin rate, 12-leads serial, arrhythmias, vitals trend, IV access, sending physician',
                    'stemi' => 'STEMI patient from outside hospital',
                    'vitals' => 'Current BP only',
                    'paper' => 'Drop patient at door with run sheet only',
                ],
                'correct' => 'transfer_sbar',
                'outcomes' => [
                    'transfer_sbar' => 'Cath lab proceeds directly to angiography suite. Fibrinolysis timing guides anticoagulation.',
                    'stemi' => 'Missing fibrinolysis time affects PCI strategy.',
                    'vitals' => 'Serial ECG and arrhythmia history critical.',
                    'paper' => 'Verbal handoff required for critical care transfer per NHTSA.',
                ],
            ],
        ],
        'explanation' => 'AHA STEMI and fibrinolysis guidelines, NHTSA critical care transport standards require continuity of infusions, serial 12-leads, arrhythmia management, and detailed interfacility handoff to PCI team.',
    ],
]);
