<?php

require_once __DIR__.'/helpers.php';

return paramedic_levels([
    [
        'title' => 'Normal Vertex Delivery — Nuchal Cord',
        'scenario' => '32-year-old G2P1 at home, crowning at your arrival. Contractions strong, fetal heart tones 140/min.',
        'steps' => [
            [
                'prompt' => 'Head delivers with tight nuchal cord that cannot be reduced. Next action?',
                'options' => [
                    'clamp_cut' => 'Clamp and cut cord between two clamps, deliver shoulders',
                    'pull' => 'Forcefully pull baby by head to reduce cord',
                    'reverse' => 'Attempt to reverse deliver head back into vagina',
                    'wait' => 'Wait for next contraction without addressing cord',
                ],
                'correct' => 'clamp_cut',
                'outcomes' => [
                    'clamp_cut' => 'Cord clamped and cut. Shoulders deliver with gentle downward traction. Baby cries spontaneously.',
                    'pull' => 'Risk of brachial plexus injury and uterine rupture.',
                    'reverse' => 'Somersault maneuver reserved for loose nuchal cord — tight cord requires clamp and cut per NIH obstetric guidance.',
                    'wait' => 'Cord compression causes fetal bradycardia and hypoxia.',
                ],
            ],
            [
                'prompt' => 'Newborn apneic, limp, pulse 80/min after delivery. Next step?',
                'options' => [
                    'dry_stim' => 'Dry, warm, stimulate; begin positive-pressure ventilation if no improvement in 30 seconds',
                    'compress' => 'Immediate chest compressions before any ventilation',
                    'momentum' => 'Hold upside down and slap feet vigorously',
                    'observe' => 'Observe 5 minutes for spontaneous respirations',
                ],
                'correct' => 'dry_stim',
                'outcomes' => [
                    'dry_stim' => 'After drying and stimulation, baby remains apneic — you begin BVM at 40 breaths/min with SpO₂ monitoring.',
                    'compress' => 'Compressions before ventilation in newborn with pulse — wrong NRP sequence.',
                    'momentum' => 'Ineffective and may cause injury; not NRP recommended.',
                    'observe' => 'Apnea with pulse 80 requires ventilation per CDC/NRP guidelines.',
                ],
            ],
        ],
        'explanation' => 'NIH and AAP Neonatal Resuscitation Program: tight nuchal cord is clamped and cut. Newborn resuscitation prioritizes warmth, drying, stimulation, then ventilation if apneic with HR >60/min per NHTSA obstetric EMS protocols.',
    ],
    [
        'title' => 'Shoulder Dystocia',
        'scenario' => 'Term labor in ambulance bay. Head delivered but retracts against perineum (turtle sign). Fetal heart tones drop to 90/min.',
        'steps' => [
            [
                'prompt' => 'First maneuver for shoulder dystocia?',
                'options' => [
                    'mcp' => 'McRoberts maneuver with suprapubic pressure',
                    'fundal' => 'Fundal pressure to push baby down',
                    'rotation' => 'Immediate Zavanelli maneuver without McRoberts',
                    'pull' => 'Excessive traction on fetal head',
                ],
                'correct' => 'mcp',
                'outcomes' => [
                    'mcp' => 'McRoberts and suprapubic pressure deliver anterior shoulder. Baby delivered with good tone.',
                    'fundal' => 'Fundal pressure worsens impaction and may cause uterine rupture.',
                    'rotation' => 'McRoberts and suprapubic pressure are first-line per NIH obstetric emergency guidance.',
                    'pull' => 'Brachial plexus injury (Erb palsy) risk with excessive traction.',
                ],
            ],
            [
                'prompt' => 'Baby delivered, cyanotic, HR 60/min, limp. Next action?',
                'options' => [
                    'ppv' => 'Positive-pressure ventilation with room air or oxygen per NRP; compressions if HR <60 after 30 sec effective PPV',
                    'intubate' => 'Immediate surgical airway in term newborn',
                    'cool' => 'Place in cold water to stimulate',
                    'delay' => 'Delay all intervention for maternal repair',
                ],
                'correct' => 'ppv',
                'outcomes' => [
                    'ppv' => 'Ventilation improves HR to 120/min. Spontaneous respirations follow.',
                    'intubate' => 'PPV with BVM is first step — intubation if BVM ineffective per NRP.',
                    'cool' => 'Hypothermia harmful — dry and warm per CDC neonatal guidance.',
                    'delay' => 'Neonatal resuscitation cannot wait for maternal third stage completion.',
                ],
            ],
        ],
        'explanation' => 'NIH shoulder dystocia algorithms: McRoberts plus suprapubic pressure first. NRP ventilation sequence for bradycardic newborn — PPV before compressions when HR >60/min per AAP NRP and FDA neonatal resuscitation standards.',
    ],
    [
        'title' => 'Postpartum Hemorrhage',
        'scenario' => 'Successful delivery 5 minutes ago. Mother pale, bleeding heavily from vagina, BP 92/58, HR 118. Uterus feels boggy on fundal massage attempt.',
        'steps' => [
            [
                'prompt' => 'Immediate management priority?',
                'options' => [
                    'uterotonics' => 'Firm fundal massage, oxytocin per protocol, IV access, high-flow oxygen',
                    'fluids_only' => 'Oral fluids and observation',
                    'manual' => 'Immediate hysterectomy in field',
                    'transport_only' => 'Transport without uterotonics or massage',
                ],
                'correct' => 'uterotonics',
                'outcomes' => [
                    'uterotonics' => 'Massage and oxytocin reduce bleeding. BP improves to 102/64 with IV fluids.',
                    'fluids_only' => 'Continued hemorrhage — postpartum hemorrhage is leading cause of maternal mortality per CDC.',
                    'manual' => 'Hysterectomy not field procedure; uterotonics and B-Lynch are hospital interventions.',
                    'transport_only' => 'Delay worsens shock from atonic uterus.',
                ],
            ],
            [
                'prompt' => 'Bleeding continues despite oxytocin. Next step per protocol?',
                'options' => [
                    'escalate' => 'Second uterotonic (misoprostol/hemabate if protocol), TXA if allowed, rapid transport to OB emergency',
                    'discharge' => 'Patient feels better — cancel transport',
                    'tamponade' => 'Attempt field balloon tamponade only if trained and protocol allows while transporting',
                    'wait' => 'Wait 30 minutes for third stage naturally',
                ],
                'correct' => 'escalate',
                'outcomes' => [
                    'escalate' => 'Additional uterotonic and TXA per WHO/CDC PPH bundles. Transport to facility with blood products.',
                    'discharge' => 'Maternal arrest from hemorrhagic shock.',
                    'tamponade' => 'May be adjunct — still requires uterotonics and surgical backup per NIH maternal safety guidance.',
                    'wait' => 'PPH >500 mL requires active management — do not observe.',
                ],
            ],
        ],
        'explanation' => 'CDC maternal mortality reviews emphasize early uterotonic administration and fundal massage for atonic PPH. NIH obstetric hemorrhage bundles include second-line uterotonics and tranexamic acid when protocol permits.',
    ],
    [
        'title' => 'Eclampsia Seizure',
        'scenario' => '36 weeks pregnant, witnessed generalized seizure now postictal. BP 182/110, 3+ edema, headache reported earlier.',
        'steps' => [
            [
                'prompt' => 'First priority after protecting airway?',
                'options' => [
                    'mag' => 'Magnesium sulfate 4–6 g IV/IM loading per protocol, left lateral positioning, BP control per protocol',
                    'diazepam' => 'Diazepam as first-line over magnesium',
                    'delivery' => 'Attempt field cesarean section',
                    'nitro' => 'Nitroglycerin for BP 182/110 only',
                ],
                'correct' => 'mag',
                'outcomes' => [
                    'mag' => 'Magnesium loaded. No recurrent seizure. BP treated per protocol. Transport to OB emergency.',
                    'diazepam' => 'Magnesium is anticonvulsant of choice in eclampsia per NIH and FDA magnesium sulfate obstetric labeling.',
                    'delivery' => 'C-section not field procedure — stabilize and transport.',
                    'nitro' => 'Does not treat eclampsia seizure; magnesium and BP control required.',
                ],
            ],
            [
                'prompt' => 'En route, recurrent seizure. Next action?',
                'options' => [
                    'repeat_mag' => 'Additional magnesium per protocol, monitor reflexes and respirations, continue transport',
                    'phenobarb' => 'Phenobarbital before repeat magnesium',
                    'restraint' => 'Restrain without medication',
                    'stop' => 'Stop ambulance until seizure ends without treatment',
                ],
                'correct' => 'repeat_mag',
                'outcomes' => [
                    'repeat_mag' => 'Second magnesium dose per protocol. Seizure terminates. Fetal heart tones monitored.',
                    'phenobarb' => 'Magnesium remains first-line for eclampsia recurrence per ACOG-aligned EMS protocols.',
                    'restraint' => 'Does not stop eclampsia — magnesium required.',
                    'stop' => 'Delays definitive care — NIH maternal safety emphasizes continuous transport.',
                ],
            ],
        ],
        'explanation' => 'FDA magnesium sulfate labeling for eclampsia and NIH preeclampsia guidance: magnesium prevents recurrent seizures. Left lateral positioning protects airway. BP control with appropriate agents per protocol.',
    ],
    [
        'title' => 'Prolapsed Cord',
        'scenario' => 'Term patient, ruptured membranes, cord visible at introitus between contractions. Fetal heart tones 70/min during episode.',
        'steps' => [
            [
                'prompt' => 'Immediate field intervention?',
                'options' => [
                    'elevate' => 'Elevate presenting part off cord with sterile gloved hand, knee-chest or Trendelenburg, high-flow O₂ to mother, emergent transport',
                    'push' => 'Push cord back into vagina forcefully',
                    'clamp' => 'Clamp visible cord',
                    'wait' => 'Wait for next contraction to deliver',
                ],
                'correct' => 'elevate',
                'outcomes' => [
                    'elevate' => 'Hand maintains off-cord pressure. FHT improve to 130/min. Rapid transport for emergency cesarean.',
                    'push' => 'Cord vasospasm worsens — NIH obstetric emergency guidance advises elevation not replacement.',
                    'clamp' => 'Clamping cord before delivery kills fetus.',
                    'wait' => 'Prolapsed cord with bradycardia is obstetric emergency — immediate cesarean needed.',
                ],
            ],
            [
                'prompt' => 'During transport, FHT drop again with contraction. Action?',
                'options' => [
                    'maintain' => 'Reconfirm hand elevation, reposition mother, oxygen, do not remove hand until OR',
                    'remove' => 'Remove hand to allow delivery in ambulance',
                    'fundal' => 'Apply fundal pressure to expedite delivery',
                    'tocolytic' => 'Tocolytic if protocol to reduce contractions while maintaining elevation',
                ],
                'correct' => 'maintain',
                'outcomes' => [
                    'maintain' => 'Continuous elevation until surgical delivery. FHT recover between contractions.',
                    'remove' => 'Fetal bradycardia returns immediately.',
                    'fundal' => 'Fundal pressure contraindicated with prolapsed cord.',
                    'tocolytic' => 'May be adjunct per protocol but elevation is critical per NHTSA obstetric protocols.',
                ],
            ],
        ],
        'explanation' => 'NIH obstetric emergencies: umbilical cord prolapse requires relieving compression by elevating presenting part and emergent cesarean. Never clamp prolapsed cord in field per CDC maternal-fetal safety guidance.',
    ],
    [
        'title' => 'Breech Delivery — Complication',
        'scenario' => 'Precipitous breech delivery en route. Body delivered, head entrapped at chin, fetal bradycardia.',
        'steps' => [
            [
                'prompt' => 'Maneuver to deliver aftercoming head?',
                'options' => [
                    'msv' => 'Mauriceau-Smellie-Veit or similar maneuver per protocol; suprapubic pressure; avoid traction',
                    'pull' => 'Pull hard on body to deliver head',
                    'rotation' => 'Rotate body 360 degrees repeatedly',
                    'csection' => 'Perform cesarean in ambulance',
                ],
                'correct' => 'msv',
                'outcomes' => [
                    'msv' => 'Head delivered with MSV maneuver. Baby requires stimulation and PPV briefly then cries.',
                    'pull' => 'Cervical spine and brachial plexus injury risk.',
                    'rotation' => 'Not standard maneuver — may worsen entrapment.',
                    'csection' => 'Not feasible in ambulance once body delivered.',
                ],
            ],
            [
                'prompt' => 'Newborn requires PPV, HR rises to 90 but remains apneic. Next?',
                'options' => [
                    'continue_ppv' => 'Continue effective PPV, increase oxygen if HR <100, compressions if HR <60 after 30 sec PPV',
                    'compress_first' => 'Compressions before establishing ventilation',
                    'stop' => 'Stop PPV when HR >60',
                    'intubate_only' => 'Delay ventilation until intubation succeeds',
                ],
                'correct' => 'continue_ppv',
                'outcomes' => [
                    'continue_ppv' => 'HR reaches 140/min. Spontaneous respirations established.',
                    'compress_first' => 'NRP requires effective ventilation before compressions when HR >60.',
                    'stop' => 'Apnea with HR 90 still requires PPV until spontaneous respirations.',
                    'intubate_only' => 'BVM PPV is first-line — intubate if BVM fails.',
                ],
            ],
        ],
        'explanation' => 'NIH breech delivery guidance describes MSV for entrapped aftercoming head. NRP algorithm: ventilation is priority for apneic newborn with HR >60/min per AAP and CDC neonatal resuscitation materials.',
    ],
    [
        'title' => 'Neonatal Resuscitation — Premature',
        'scenario' => '34-week precipitous delivery in ED parking lot. Baby 2.1 kg, limp, HR 80/min, apneic.',
        'steps' => [
            [
                'prompt' => 'Initial NRP steps?',
                'options' => [
                    'warm_ppv' => 'Plastic wrap/warming, dry, position airway, begin PPV with appropriate mask; pulse ox on right hand',
                    'intubate' => 'Immediate intubation without PPV trial',
                    'delayed' => 'Delayed cord clamping 3 minutes before any care',
                    'cpr' => 'Chest compressions first',
                ],
                'correct' => 'warm_ppv',
                'outcomes' => [
                    'warm_ppv' => 'Warmth and PPV improve HR to 120/min. SpO₂ targeted per NRP prematurity curve.',
                    'intubate' => 'PPV with BVM first unless ineffective — premature infants need gentle ventilation.',
                    'delayed' => 'Resuscitation takes priority over delayed clamping when infant requires intervention.',
                    'cpr' => 'HR 80/min — ventilate first per NRP.',
                ],
            ],
            [
                'prompt' => 'HR remains 50/min after 30 seconds effective PPV. Next?',
                'options' => [
                    'compress' => 'Begin chest compressions coordinated with ventilation 3:1 ratio for newborn',
                    'epi' => 'Epinephrine before compressions',
                    'stop' => 'Terminate resuscitation at 5 minutes',
                    'cool' => 'Cool infant to reduce metabolic demand',
                ],
                'correct' => 'compress',
                'outcomes' => [
                    'compress' => 'Compressions with ventilation improve HR. Epinephrine considered if HR remains <60.',
                    'epi' => 'Epinephrine after adequate ventilation and compressions if HR <60 per NRP.',
                    'stop' => 'Premature resuscitation continues per medical control and NRP termination criteria.',
                    'cool' => 'Hypothermia harmful in prematurity — maintain warmth per NIH neonatal guidance.',
                ],
            ],
        ],
        'explanation' => 'AAP NRP and NIH prematurity resources emphasize thermoregulation (plastic wrap), gentle PPV, and SpO₂ targets. Compressions when HR <60/min after 30 seconds effective ventilation using 3:1 newborn ratio.',
    ],
    [
        'title' => 'Placenta Previa Bleeding',
        'scenario' => 'Third trimester patient, painless bright red vaginal bleeding, BP 102/68, HR 108, no labor contractions.',
        'steps' => [
            [
                'prompt' => 'Prehospital management priority?',
                'options' => [
                    'no_exam' => 'No vaginal exam, IV access, monitor fetus, left tilt, rapid transport to OB emergency',
                    'speculum' => 'Speculum exam to confirm source',
                    'delivery' => 'Attempt vaginal delivery in field',
                    'fundal' => 'Fundal massage for bleeding',
                ],
                'correct' => 'no_exam',
                'outcomes' => [
                    'no_exam' => 'Transport initiated. OB team prepares for possible cesarean. Bleeding stable en route.',
                    'speculum' => 'Vaginal exam may worsen hemorrhage if placenta previa — contraindicated per NIH obstetric guidance.',
                    'delivery' => 'Vaginal delivery contraindicated with previa bleeding.',
                    'fundal' => 'Fundal massage for atonic uterus not previa — may cause harm.',
                ],
            ],
            [
                'prompt' => 'En route, BP drops to 86/50, bleeding increases. Next?',
                'options' => [
                    'shock' => 'Two large-bore IVs, crystalloid bolus, O₂, type O-negative if protocol, expedite transport',
                    'exam' => 'Digital vaginal exam to check dilation',
                    'tocolytic' => 'Tocolytic to stop bleeding',
                    'wait' => 'Wait for hemorrhage to stop spontaneously',
                ],
                'correct' => 'shock',
                'outcomes' => [
                    'shock' => 'Fluids and oxygen support perfusion. Massive transfusion protocol activated at receiving hospital.',
                    'exam' => 'Digital exam contraindicated — may trigger catastrophic hemorrhage.',
                    'tocolytic' => 'Does not treat previa hemorrhage or maternal shock.',
                    'wait' => 'Placenta previa hemorrhage can be massive — requires surgical delivery.',
                ],
            ],
        ],
        'explanation' => 'NIH placenta previa guidance: avoid vaginal examination, resuscitate maternal hemorrhagic shock, monitor fetus, emergent cesarean. CDC maternal safety reviews highlight previa as cause of preventable hemorrhage death.',
    ],
    [
        'title' => 'Meconium — Depressed Newborn',
        'scenario' => 'Term delivery in ambulance. Thick meconium at delivery. Baby limp, apneic, HR 70/min.',
        'steps' => [
            [
                'prompt' => 'Per current NRP, initial action for non-vigorous meconium-stained newborn?',
                'options' => [
                    'ppv_first' => 'Initiate PPV if apneic or HR <100; intubate for suction if airway obstructed — routine intrapartum suction no longer recommended',
                    'suction_trachea' => 'Mandatory immediate tracheal suction before any ventilation for all meconium',
                    'shake' => 'Vigorous shaking to clear meconium',
                    'observe' => 'Observe only if meconium present',
                ],
                'correct' => 'ppv_first',
                'outcomes' => [
                    'ppv_first' => 'PPV initiated. HR improves. Intubation and suction if ventilation ineffective due to obstruction.',
                    'suction_trachea' => 'NRP no longer recommends routine intrapartum tracheal suction — delays ventilation per AAP 2020+ guidelines.',
                    'shake' => 'Not recommended — may cause injury.',
                    'observe' => 'Apneic depressed newborn requires immediate resuscitation.',
                ],
            ],
            [
                'prompt' => 'After PPV, HR 90 but persistent poor tone. Next?',
                'options' => [
                    'continue' => 'Continue PPV, consider intubation for obstruction, compressions if HR <60 after 30 sec effective ventilation',
                    'stop' => 'Stop ventilation — meconium always fatal',
                    'chest_only' => 'Compressions only without ventilation',
                    'cool' => 'Cool baby to reduce metabolic rate',
                ],
                'correct' => 'continue',
                'outcomes' => [
                    'continue' => 'Intubation clears meconium plug. HR normalizes. Transport to NICU.',
                    'stop' => 'Meconium aspiration treatable with ventilation and NICU care per NIH neonatal guidance.',
                    'chest_only' => 'Ventilation before compressions when HR >60.',
                    'cool' => 'Thermoregulation requires warmth not cooling.',
                ],
            ],
        ],
        'explanation' => 'AAP NRP 2020+ and NIH meconium guidance: non-vigorous infants receive standard resuscitation — PPV priority, not routine tracheal suction. Intubate for airway obstruction if PPV ineffective.',
    ],
    [
        'title' => 'Umbilical Cord Clamping — Resuscitation Need',
        'scenario' => 'Vaginal delivery at scene. Baby vigorous, crying, good tone. Mother stable. No complications.',
        'steps' => [
            [
                'prompt' => 'Appropriate neonatal care for vigorous term newborn?',
                'options' => [
                    'routine' => 'Dry, skin-to-skin or warm, delayed cord clamping if protocol, APGAR assessment, support breastfeeding',
                    'intubate' => 'Prophylactic intubation all newborns',
                    'immediate_cut' => 'Immediate cord clamp within 10 seconds regardless of status',
                    'separate' => 'Separate from mother immediately for nursery care',
                ],
                'correct' => 'routine',
                'outcomes' => [
                    'routine' => 'Vigorous newborn stays with mother, warmth maintained, cord clamped per protocol timing.',
                    'intubate' => 'Unnecessary for vigorous infant per NRP.',
                    'immediate_cut' => 'Delayed clamping benefits term infants when no resuscitation needed per NIH cord clamping reviews.',
                    'separate' => 'Skin-to-skin supports thermoregulation and bonding per CDC maternal-infant guidance.',
                ],
            ],
            [
                'prompt' => 'At 5 minutes, baby feeding well but mild acrocyanosis, HR 130, RR 45. Action?',
                'options' => [
                    'reassure' => 'Reassure — acrocyanosis common first hours; continue warming and monitoring during transport if indicated',
                    'ppv' => 'Begin PPV for acrocyanosis alone',
                    'cool' => 'Cool to treat cyanosis',
                    'admit_nicu' => 'Mandatory NICU for all acrocyanosis',
                ],
                'correct' => 'reassure',
                'outcomes' => [
                    'reassure' => 'Central pink, acrocyanosis resolves with warming. Routine postpartum transport if mother needs evaluation.',
                    'ppv' => 'PPV not indicated with normal HR and RR — only if respiratory distress.',
                    'cool' => 'Worsens peripheral cyanosis.',
                    'admit_nicu' => 'Normal vital signs do not require NICU for acrocyanosis alone.',
                ],
            ],
        ],
        'explanation' => 'AAP NRP and CDC newborn care guidance: vigorous infants need warmth, drying, and routine care with delayed cord clamping when appropriate. Acrocyanosis with normal vitals is benign if core perfusion adequate.',
    ],
]);
