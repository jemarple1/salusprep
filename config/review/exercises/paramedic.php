<?php

return [
    [
        'slug' => 'patient-assessment',
        'exercise_slug' => 'patient-assessment',
        'title' => 'Paramedic Assessment & Clinical Decision Making',
        'excerpt' => 'Integrating primary and secondary surveys with evolving scenarios to choose assessments and interventions in logical sequence.',
        'category' => 'Assessment',
        'accent' => 'medic',
        'keywords' => ['assessment', 'primary survey', 'secondary', 'clinical decision', 'differential'],
        'sources' => [
            ['id' => 1, 'label' => 'NHTSA — Emergency Medical Services', 'url' => 'https://www.nhtsa.gov/emergency-medical-services'],
            ['id' => 2, 'label' => 'CDC — Guidelines for Field Triage of Injured Patients', 'url' => 'https://www.cdc.gov/fieldtriage/'],
        ],
        'sections' => [
            [
                'heading' => 'Structured assessment under uncertainty',
                'paragraphs' => [
                    'Paramedic education emphasizes repeatable assessment frameworks that hold when patients decompensate mid-call. NHTSA national EMS initiatives align training with scene safety, primary ABCDE survey, and targeted history—OPQRST and SAMPLE—before exhaustive secondary exam.<sup>1</sup> Branching scenarios test whether you treat life threats before chasing incidental findings.',
                    'Clinical decision making links assessment data to differential diagnoses: crushing chest pain with hypotension suggests cardiogenic shock or massive MI, not musculoskeletal strain. A single abnormal vital sign rarely defines the picture—combine mechanism, exam, and trend. Reassessment after each intervention closes the loop and reveals whether your working diagnosis was correct.',
                ],
            ],
            [
                'heading' => 'When the patient changes after your choice',
                'paragraphs' => [
                    'Dynamic scenarios simulate real calls: nitroglycerin may drop pressure; bronchodilators may transiently increase heart rate; bleeding control may unmask tension physiology. The CDC field triage philosophy—identify abnormal physiology early—applies continuously, not only at first contact.<sup>2</sup>',
                    'Document mental status, skin, lung sounds, and vitals after every major decision. If the scenario offers ALS intercept or air medical, criteria often include refractory shock, difficult airway, or time-critical neurovascular injury. Choosing transport mode is part of assessment, not an afterthought.',
                ],
            ],
        ],
    ],
    [
        'slug' => 'rhythm-12lead',
        'exercise_slug' => 'rhythm-12lead',
        'title' => 'Rhythm Recognition & 12-Lead ECG Interpretation',
        'excerpt' => 'Matching rate, regularity, waveforms, and ST-segment patterns to rhythm diagnoses and STEMI localization clues.',
        'category' => 'Cardiac',
        'accent' => 'rescue',
        'keywords' => ['ecg', '12-lead', 'rhythm', 'stemi', 'arrhythmia'],
        'sources' => [
            ['id' => 1, 'label' => 'NIH NHLBI — Heart Attack', 'url' => 'https://www.nhlbi.nih.gov/health/heart-attack'],
            ['id' => 2, 'label' => 'CDC — Heart Disease', 'url' => 'https://www.cdc.gov/heart-disease/'],
        ],
        'sections' => [
            [
                'heading' => 'Rhythm analysis sequence',
                'paragraphs' => [
                    'Start with rate and regularity, then P waves, PR interval, QRS width, and relationship between P and QRS. Narrow-complex tachycardia with absent P waves suggests SVT or atrial fibrillation depending on irregularity. Wide-complex tachycardia is ventricular tachycardia until proven otherwise—treat per unstable tachycardia algorithm.',
                    'The NHLBI describes heart attack as blocked coronary blood flow; prehospital 12-lead acquisition identifies ST-elevation myocardial infarction patterns that activate cath lab teams.<sup>1</sup> Compare current ECG to prior when available; new ST elevation or new bundle branch block may meet STEMI criteria even with nonspecific symptoms.',
                ],
            ],
            [
                'heading' => 'STEMI patterns and mimics',
                'paragraphs' => [
                    'ST elevation in anatomically contiguous leads localizes infarct territory—anterior (V1–V4), inferior (II, III, aVF), lateral (I, aVL, V5–V6). Reciprocal depression supports acute ischemia. The CDC notes heart disease remains a leading killer; rapid recognition shortens door-to-balloon time.<sup>2</sup>',
                    'Mimics include benign early repolarization, pericarditis, hyperkalemia peaked T waves, and paced rhythms. Hyperacute T waves may precede ST elevation—serial 12-leads during transport capture evolution. Document rhythm, rate, and STEMI alert transmission time on the PCR.',
                ],
            ],
        ],
    ],
    [
        'slug' => 'cardiology-treatment',
        'exercise_slug' => 'cardiology-treatment',
        'title' => 'ACLS Cardiac Algorithm Prioritization',
        'excerpt' => 'Sequencing pacing, defibrillation, medications, and supportive care for bradycardia, tachycardia, and arrest rhythms.',
        'category' => 'Cardiac',
        'accent' => 'rescue',
        'keywords' => ['acls', 'bradycardia', 'tachycardia', 'arrest', 'epinephrine', 'amiodarone'],
        'sources' => [
            ['id' => 1, 'label' => 'NIH NHLBI — CPR', 'url' => 'https://www.nhlbi.nih.gov/health/cpr'],
            ['id' => 2, 'label' => 'CDC — About Cardiac Arrest', 'url' => 'https://www.cdc.gov/heart-disease/about/cardiac-arrest.htm'],
        ],
        'sections' => [
            [
                'heading' => 'Unstable versus stable pathways',
                'paragraphs' => [
                    'Unstable patients with chest pain, hypotension, altered mental status, or acute heart failure need immediate rhythm-specific intervention—synchronized cardioversion for unstable tachycardia with pulses, transcutaneous pacing for unstable bradycardia, or defibrillation for pulseless VT/VF. Stable patients may receive medications and monitoring while preparing transport.',
                    'Cardiac arrest care prioritizes high-quality CPR and early defibrillation for shockable rhythms.<sup>1</sup> Epinephrine every 3–5 minutes and amiodarone or lidocaine for refractory VF/VT follow ACLS sequencing—compressions pause only for rhythm checks and shocks. Capnography confirms airway placement and tracks perfusion during resuscitation.',
                ],
            ],
            [
                'heading' => 'Ranking interventions on exam items',
                'paragraphs' => [
                    'The CDC emphasizes that cardiac arrest is sudden and often fatal without immediate action—algorithm drills train order discipline under stress.<sup>2</sup> For symptomatic bradycardia: atropine when appropriate, then pacing if perfusion remains inadequate. For stable narrow-complex tachycardia, vagal maneuvers and adenosine precede beta-blockers or calcium channel blockers per protocol.',
                    'Post-ROSC care includes blood pressure support, 12-lead ECG, targeted temperature management per medical direction, and transport to capable facility. Rank items that restore perfusion before those that merely improve numbers—pacing before repeat atropine when HR stays 30 with altered mental status.',
                ],
            ],
        ],
    ],
    [
        'slug' => 'airway-respiratory',
        'exercise_slug' => 'airway-respiratory',
        'title' => 'Advanced Airway & Respiratory Management',
        'excerpt' => 'Categorizing basic adjuncts, supraglottic devices, intubation, BVM, CPAP, and rescue techniques for failing ventilation.',
        'category' => 'Airway',
        'accent' => 'ems',
        'keywords' => ['airway', 'intubation', 'bvm', 'cpap', 'respiratory', 'ventilation'],
        'sources' => [
            ['id' => 1, 'label' => 'NIH MedlinePlus — Breathing difficulty', 'url' => 'https://medlineplus.gov/breathingproblems.html'],
            ['id' => 2, 'label' => 'CDC — Chronic Obstructive Pulmonary Disease (COPD)', 'url' => 'https://www.cdc.gov/copd/'],
        ],
        'sections' => [
            [
                'heading' => 'Tool selection by patient and problem',
                'paragraphs' => [
                    'Airway management escalates from positioning and suction through oropharyngeal and nasopharyngeal adjuncts, bag-valve-mask ventilation, supraglottic airways, and endotracheal intubation. MedlinePlus lists severe breathing difficulty as requiring emergency care—paramedics match device to obstruction level, gag reflex, and anticipated difficult airway.<sup>1</sup>',
                    'BVM with two-person technique and airway adjunct is first-line when apneic or critically hypoventilating. Supraglottic devices bridge when BVM is inadequate but intubation is delayed. CPAP supports alert patients with pulmonary edema or COPD exacerbation without immediate intubation—monitor for vomiting and declining mental status.',
                ],
            ],
            [
                'heading' => 'Ventilation strategies and pitfalls',
                'paragraphs' => [
                    'The CDC COPD resources remind clinicians that chronic retainers may depend on hypoxic drive—titrate oxygen to relieve hypoxia without eliminating hypoxic ventilatory stimulus when protocol allows targeted saturations.<sup>2</sup> Hyperventilation in arrest or head injury worsens outcomes; follow rate and tidal volume guidelines.',
                    'Confirm placement with capnography, bilateral chest rise, and absent gastric sounds—not color change alone. RSI requires sedative and paralytic sequencing with backup BVM and suction ready. Post-intubation seduction and paralysis management continues through transport; document EtCO₂ waveform throughout.',
                ],
            ],
        ],
    ],
    [
        'slug' => 'pharmacology-mastery',
        'exercise_slug' => 'pharmacology-mastery',
        'title' => 'Weight-Based Paramedic Pharmacology',
        'excerpt' => 'Calculating mg/kg doses for pediatrics and adults using FDA labeling concentrations and protocol maximums.',
        'category' => 'Pharmacology',
        'accent' => 'pharma',
        'keywords' => ['pharmacology', 'weight-based', 'dose calculation', 'pediatric', 'mg/kg'],
        'sources' => [
            ['id' => 1, 'label' => 'FDA — Index to Drug-Specific Information', 'url' => 'https://www.fda.gov/drugs/drug-approvals-and-databases/index-drug-specific-information'],
            ['id' => 2, 'label' => 'NIH DailyMed — FDA drug labels', 'url' => 'https://dailymed.nlm.nih.gov/dailymed/'],
        ],
        'sections' => [
            [
                'heading' => 'Calculation discipline',
                'paragraphs' => [
                    'Paramedic dosing errors cluster around unit confusion—milligrams per kilogram versus total milligrams, and milliliters of concentrated epinephrine versus milligrams delivered. FDA-approved labels on DailyMed specify concentration, route, and usual dose ranges EMS protocols adapt.<sup>1</sup> Always write: weight × mg/kg = total mg; then total mg ÷ concentration mg/mL = mL to administer.',
                    'Broselow tape or length-based systems estimate weight when children cannot be weighed—know when your protocol mandates actual weight for specific drugs. Round per protocol rules; never exceed single-dose or cumulative maximums even when math yields higher numbers.',
                ],
            ],
            [
                'heading' => 'High-risk medication classes',
                'paragraphs' => [
                    'Epinephrine, amiodarone, adenosine, fentanyl, ketamine, rocuronium, and pediatric fluid boluses appear frequently in calculation drills.<sup>2</sup> Double-check route: IM anaphylaxis epinephrine concentration differs from IV cardiac arrest epinephrine. Intranasal and intramuscular routes have different volume limits.',
                    'Document calculated dose, concentration used, volume drawn, and waste policy compliance. Medical direction may order off-protocol doses—record physician name, order, and repeat calculation with a partner for high-alert medications when policy requires.',
                ],
            ],
        ],
    ],
    [
        'slug' => 'shock-hemodynamics',
        'exercise_slug' => 'shock-hemodynamics',
        'title' => 'Shock States & Hemodynamic Profiles',
        'excerpt' => 'Distinguishing hypovolemic, cardiogenic, distributive, and obstructive shock by clinical findings and capillary refill patterns.',
        'category' => 'Medical',
        'accent' => 'medic',
        'keywords' => ['shock', 'hemodynamics', 'hypovolemic', 'cardiogenic', 'septic', 'obstructive'],
        'sources' => [
            ['id' => 1, 'label' => 'NIH MedlinePlus — Shock', 'url' => 'https://medlineplus.gov/ency/article/000167.htm'],
            ['id' => 2, 'label' => 'CDC — Sepsis', 'url' => 'https://www.cdc.gov/sepsis/'],
        ],
        'sections' => [
            [
                'heading' => 'Four shock buckets',
                'paragraphs' => [
                    'MedlinePlus defines shock as inadequate tissue perfusion—the body compensates with tachycardia and vasoconstriction before blood pressure falls.<sup>1</sup> Hypovolemic shock from hemorrhage or dehydration shows flat neck veins, cool skin, and weak pulses. Cardiogenic shock from pump failure may show JVD, pulmonary edema, and narrow pulse pressure.',
                    'Distributive shock (septic, anaphylactic, neurogenic) features warm flushed skin early in sepsis or spinal injury, with vasodilation and relative hypovolemia. Obstructive shock from tension pneumothorax or massive PE shows obstructed venous return—JVD with hypotension and clear lungs in tension; acute right heart strain in massive PE.',
                ],
            ],
            [
                'heading' => 'Field identification and treatment alignment',
                'paragraphs' => [
                    'The CDC sepsis campaign urges rapid recognition and treatment—EMS alerts for fever, tachycardia, and altered mental status shorten time to antibiotics.<sup>2</sup> Anaphylactic distributive shock needs epinephrine IM first; cardiogenic shock may worsen with aggressive fluid boluses; obstructive shock from tension needs decompression before fluids.',
                    'Capnography EtCO₂ may be low in hypoperfusion. Lactate is hospital-side but clinical perfusion assessment drives prehospital priorities: stop bleeding, restore airway, decompress chest, epinephrine for anaphylaxis, norepinephrine or push-dose pressors per protocol when available. Reassess after each bolus or pressor—lung crackles may signal fluid overload in cardiogenic failure.',
                ],
            ],
        ],
    ],
    [
        'slug' => 'trauma-management',
        'exercise_slug' => 'trauma-management',
        'title' => 'Multi-System Trauma Intervention Priority',
        'excerpt' => 'Ranking hemorrhage control, airway management, and stabilization before definitive fracture care in complex trauma.',
        'category' => 'Trauma',
        'accent' => 'safety',
        'keywords' => ['trauma', 'hemorrhage', 'mci', 'stabilization', 'priority'],
        'sources' => [
            ['id' => 1, 'label' => 'CDC — Guidelines for Field Triage of Injured Patients', 'url' => 'https://www.cdc.gov/fieldtriage/'],
            ['id' => 2, 'label' => 'CDC — Traumatic Brain Injury', 'url' => 'https://www.cdc.gov/traumaticbraininjury/'],
        ],
        'sections' => [
            [
                'heading' => 'The lethal problems first',
                'paragraphs' => [
                    'Multi-system trauma tempts providers to splint dramatic deformities while missed hemorrhage kills silently. CDC field triage criteria prioritize abnormal physiology—hypotension, altered mental status, respiratory compromise—for trauma center transport.<sup>1</sup> Intervention order mirrors primary survey: catastrophic external bleeding, airway, tension pneumothorax, inadequate breathing, then circulation with pelvic binders and shock management.',
                    'Open fractures need sterile dressing and hemorrhage control—not immediate detailed bone manipulation in the hot zone. Spinal motion restriction follows life threat stabilization when mechanism warrants; log-roll exposes posterior wounds without delaying critical interventions.',
                ],
            ],
            [
                'heading' => 'Packaging and transport decisions',
                'paragraphs' => [
                    'Traumatic brain injury compounds mortality when hypoxia and hypotension coexist—the CDC highlights TBI as a major disability cause; prevent secondary injury with oxygenation and blood pressure support.<sup>2</sup> GCS trends matter more than isolated extremity injuries for destination choice.',
                    'Rank pelvic binder application before long-board padding debates when pelvic instability is suspected. Reassess tourniquets and dressings en route. Communicate blood products need, massive transfusion protocol activation, and ETA so trauma teams prepare OR capacity before arrival.',
                ],
            ],
        ],
    ],
    [
        'slug' => 'stroke-neurology',
        'exercise_slug' => 'stroke-neurology',
        'title' => 'ALS Stroke & Neurologic Emergencies',
        'excerpt' => 'FAST-positive screening, glucose checks, blood pressure nuance, and stroke-center transport for time-sensitive neuro care.',
        'category' => 'Neurology',
        'accent' => 'medic',
        'keywords' => ['stroke', 'fast', 'neurology', 'tpa', 'thrombectomy'],
        'sources' => [
            ['id' => 1, 'label' => 'CDC — Stroke', 'url' => 'https://www.cdc.gov/stroke/'],
            ['id' => 2, 'label' => 'NIH MedlinePlus — Stroke', 'url' => 'https://medlineplus.gov/stroke.html'],
        ],
        'sections' => [
            [
                'heading' => 'Prehospital stroke bundle',
                'paragraphs' => [
                    'The CDC urges calling EMS immediately when stroke symptoms appear—ambulance arrival triggers hospital stroke team activation and shortens treatment delays.<sup>1</sup> Document last known well time, perform FAST or BE-FAST exam, check blood glucose, and establish IV access per protocol. Seizure at onset does not exclude stroke but requires glucose and airway assessment first.',
                    'Large vessel occlusion may present with minimal cortical signs—gaze preference, neglect, or isolated aphasia. Prehospital stroke scales (CPSS, RACE, LAMS) supplement FAST for severity estimation and bypass decisions to thrombectomy-capable centers when protocol allows.',
                ],
            ],
            [
                'heading' => 'Blood pressure and transport nuance',
                'paragraphs' => [
                    'MedlinePlus lists sudden weakness, confusion, and severe headache as stroke warnings requiring emergency evaluation.<sup>2</sup> Permissive hypertension is often maintained prehospital for acute stroke—aggressive lowering may reduce perfusion to ischemic penumbra unless protocol specifies exceptions for extreme pressures with concurrent MI or heart failure.',
                    'Avoid oral intake; position for airway protection if decreased consciousness. Notify receiving facility with ETA, glucose, vitals, and exam findings. Post-stroke seizure management follows benzodiazepine protocol without delaying transport—time loss directly reduces intervention eligibility.',
                ],
            ],
        ],
    ],
    [
        'slug' => 'medical-emergencies',
        'exercise_slug' => 'medical-emergencies',
        'title' => 'Medical Emergency Pattern Recognition',
        'excerpt' => 'Matching symptom clusters to likely diagnoses—DKA, pulmonary embolism, adrenal crisis, and toxicologic presentations.',
        'category' => 'Medical',
        'accent' => 'pharma',
        'keywords' => ['medical emergency', 'dka', 'pe', 'adrenal', 'toxicology', 'diagnosis'],
        'sources' => [
            ['id' => 1, 'label' => 'NIH MedlinePlus — Medical Encyclopedia', 'url' => 'https://medlineplus.gov/encyclopedia.html'],
            ['id' => 2, 'label' => 'CDC — Diabetes', 'url' => 'https://www.cdc.gov/diabetes/'],
        ],
        'sections' => [
            [
                'heading' => 'Classic clusters',
                'paragraphs' => [
                    'Medical emergency drills pair presentations with pathways: Kussmaul respirations, hyperglycemia, and dehydration suggest diabetic ketoacidosis; sudden pleuritic chest pain with tachycardia and hypoxia suggest pulmonary embolism; hypotension with hyperpigmented skin history and vomiting suggest adrenal crisis. MedlinePlus encyclopedia entries support connecting symptom constellations to conditions.<sup>1</sup>',
                    'Toxidromes narrow unknown ingestions: cholinergic (SLUDGE), anticholinergic (hot as a hare, mad as a hatter), opioid (miosis, respiratory depression), sympathomimetic (tachycardia, agitation, dilated pupils). Environmental exposures—carbon monoxide with multiple victims and headache—require scene safety and high-flow oxygen.',
                ],
            ],
            [
                'heading' => 'Treatment pathway selection',
                'paragraphs' => [
                    'The CDC diabetes program notes hyperglycemic emergencies as acute complications requiring rapid care—EMS gives fluids and insulin only per protocol, with glucose monitoring and potassium awareness for DKA.<sup>2</sup> PE with hypotension may need fluids cautiously and push-dose epinephrine when right ventricular failure dominates—not blind large boluses.',
                    'Adrenal crisis receives hydrocortisone and glucose per medical direction. Calcium channel blocker overdose may respond to calcium and high-dose insulin therapy at hospital; prehospital focus is airway and perfusion support. Match pathway to primary problem before treating incidental vitals.',
                ],
            ],
        ],
    ],
    [
        'slug' => 'pediatrics-emergency',
        'exercise_slug' => 'pediatrics-emergency',
        'title' => 'Pediatric Dosing & Fluid Resuscitation',
        'excerpt' => 'Weight-based drug and fluid calculations for infants and children using Broselow and protocol maximum safeguards.',
        'category' => 'Pediatrics',
        'accent' => 'medic',
        'keywords' => ['pediatric', 'dosing', 'fluid bolus', 'broselow', 'weight-based'],
        'sources' => [
            ['id' => 1, 'label' => 'CDC — Child Safety and Injury Prevention', 'url' => 'https://www.cdc.gov/child-safety/'],
            ['id' => 2, 'label' => 'NIH MedlinePlus — Pediatric health', 'url' => 'https://medlineplus.gov/ency/article/002456.htm'],
        ],
        'sections' => [
            [
                'heading' => 'Pediatric resuscitation fundamentals',
                'paragraphs' => [
                    'Children have higher metabolic rates, smaller functional reserve, and age-specific vital sign normals. The CDC child safety program stresses injury prevention and rapid EMS access when pediatric emergencies occur.<sup>1</sup> Bradycardia in infants is often pre-arrest from hypoxia—ventilate before epinephrine when heart rate is low with poor perfusion.',
                    'Weight-based epinephrine for anaphylaxis and arrest, defibrillation joules (2–4 J/kg), and dextrose doses (0.5–1 g/kg) depend on accurate weight. Length-based tapes estimate weight when parents cannot report it—know color zone and corresponding kilograms for your equipment.',
                ],
            ],
            [
                'heading' => 'Fluids and equipment sizing',
                'paragraphs' => [
                    'Isotonic crystalloid boluses for shock are commonly 20 mL/kg—repeat per protocol with reassessment between aliquots to avoid fluid overload in cardiogenic failure.<sup>2</sup> Endotracheal tube size by age formula (age/4 + 4 uncuffed) guides airway planning; have smaller backup tubes ready.',
                    'Family presence during resuscitation may be supported per policy—communicate clearly while maintaining procedural focus. Hypoglycemia in infants may present with jitteriness or seizures; check glucose early. Transport with warming measures—hypothermia worsens coagulopathy and resuscitation outcomes in small patients.',
                ],
            ],
        ],
    ],
    [
        'slug' => 'obstetrics-neonatal',
        'exercise_slug' => 'obstetrics-neonatal',
        'title' => 'Obstetric & Neonatal Resuscitation',
        'excerpt' => 'Branching delivery complications, NRP-style newborn resuscitation, and maternal hemorrhage priorities for ALS crews.',
        'category' => 'OB/Peds',
        'accent' => 'pharma',
        'keywords' => ['obstetrics', 'neonatal', 'nrp', 'delivery', 'postpartum hemorrhage'],
        'sources' => [
            ['id' => 1, 'label' => 'CDC — Pregnancy', 'url' => 'https://www.cdc.gov/pregnancy/'],
            ['id' => 2, 'label' => 'NIH MedlinePlus — Childbirth', 'url' => 'https://medlineplus.gov/ency/article/002002.htm'],
        ],
        'sections' => [
            [
                'heading' => 'Maternal emergencies on scene',
                'paragraphs' => [
                    'The CDC tracks maternal health outcomes—EMS plays a role when complications arise outside hospitals.<sup>1</sup> Eclampsia presents with seizures in pregnancy—magnesium sulfate per protocol and airway protection precede transport. Placenta previa and abruptio may cause painless or painful bleeding with shock; large-bore IV access and rapid transport outweigh field ultrasound attempts.',
                    'Shoulder dystocia branching scenarios test McRoberts positioning, suprapubic pressure, and medical direction for advanced maneuvers—never fundal pressure. Postpartum hemorrhage after delivery requires uterine massage, oxytocin per protocol, and blood product readiness at receiving facility notification.',
                ],
            ],
            [
                'heading' => 'Neonatal resuscitation sequence',
                'paragraphs' => [
                    'MedlinePlus childbirth guidance emphasizes immediate drying, warming, and stimulation for most newborns.<sup>2</sup> NRP algorithm: provide warmth, dry, stimulate; assess breathing and tone—if apneic or gasping with HR below 100, start positive pressure ventilation. Increase FiO₂ per protocol for term infants; chest compressions when HR remains below 60 after 30 seconds of effective ventilation.',
                    'Epinephrine and volume expansion follow when compressions fail to raise heart rate—use correct ET tube size and capnography when intubating neonates. Two-rescuer cord clamping and thermal protection continue through transport; keep mother and baby together when both are stable for warmth and emotional support.',
                ],
            ],
        ],
    ],
    [
        'slug' => 'ems-operations-mci',
        'exercise_slug' => 'ems-operations-mci',
        'title' => 'EMS Operations & Mass-Casualty Incident Management',
        'excerpt' => 'Incident command roles, START/SALT triage, and transport officer decisions when casualties exceed local resources.',
        'category' => 'Operations',
        'accent' => 'rescue',
        'keywords' => ['mci', 'incident command', 'start', 'salt', 'transport officer', 'operations'],
        'sources' => [
            ['id' => 1, 'label' => 'NHTSA — Emergency Medical Services', 'url' => 'https://www.nhtsa.gov/emergency-medical-services'],
            ['id' => 2, 'label' => 'CDC — Guidelines for Field Triage of Injured Patients', 'url' => 'https://www.cdc.gov/fieldtriage/'],
        ],
        'sections' => [
            [
                'heading' => 'ICS structure for EMS',
                'paragraphs' => [
                    'Mass-casualty incidents require incident command system activation—operations, planning, logistics, and finance/administration scale with event size. NHTSA EMS leadership training integrates NIMS/ICS for multi-agency response.<sup>1</sup> EMS branch director coordinates triage, treatment, and transport sectors without individual crews self-deploying to favorite patients.',
                    'Establish clear entry and exit routes, staging for ambulances, and casualty collection points upgraded from START/SALT tags. Communications plan includes dedicated channels and hospital diversion status updates—transport officer matches patient priority to remaining vehicle inventory.',
                ],
            ],
            [
                'heading' => 'Resource allocation drills',
                'paragraphs' => [
                    'When immediate patients outnumber ambulances, transport officer selects who moves based on reversible life threat and time to definitive care—not who appears most distressed visually.<sup>2</sup> Re-triage delayed zone patients each cycle; deterioration upgrades priority.',
                    'Documentation includes triage tag number, treatments given in treatment sector, and destination hospital to support reunification and forensic investigation. Demobilization and crew rehab are operational necessities—fatigued paramedics degrade triage accuracy in prolonged incidents.',
                ],
            ],
        ],
    ],
    [
        'slug' => 'soap-charting',
        'exercise_slug' => 'soap-charting',
        'title' => 'Advanced ALS Documentation & SOAP Narratives',
        'excerpt' => 'Building paramedic PCRs that capture interventions, reassessment, medical direction, and critical time stamps for ALS calls.',
        'category' => 'Documentation',
        'accent' => 'ems',
        'keywords' => ['soap', 'documentation', 'als', 'pcr', 'narrative'],
        'sources' => [
            ['id' => 1, 'label' => 'NHTSA — Emergency Medical Services', 'url' => 'https://www.nhtsa.gov/emergency-medical-services'],
            ['id' => 2, 'label' => 'AHRQ — Patient Safety Network', 'url' => 'https://psnet.ahrq.gov/'],
        ],
        'sections' => [
            [
                'heading' => 'ALS objective data density',
                'paragraphs' => [
                    'Paramedic narratives include 12-lead interpretations, EtCO₂ values, multiple vitals sets, and waveform capnography trends—not only initial findings. National EMS quality measures increasingly audit advanced interventions for indication and outcome.<sup>1</sup> Objective section lists device settings: CPAP pressure, ventilator rate, tourniquet time, and fluid volumes with rates.',
                    'Assessment states working field impressions—acute coronary syndrome, septic shock, status asthmaticus—without claiming hospital diagnoses. Plan documents medications with dose, route, time, response, and physician orders for off-protocol actions. Discard duplicate vitals and non-clinical scene commentary that obscures handoff.',
                ],
            ],
            [
                'heading' => 'Legal and QA readiness',
                'paragraphs' => [
                    'AHRQ links incomplete documentation to adverse event investigation gaps and billing disputes.<sup>2</sup> Time stamps for symptom onset, first EMS contact, key interventions, and facility notification support stroke, STEMI, and trauma registry accuracy.',
                    'Refusal and no-transport calls need capacity assessment, risks explained, signature or witness, and base station contact when required. Reassessment paragraphs prove continued patient engagement—especially when initial plan failed and backup strategies were deployed.',
                ],
            ],
        ],
    ],
    [
        'slug' => 'full-als-scenario',
        'exercise_slug' => 'full-als-scenario',
        'title' => 'Full ALS Call Simulation: Dispatch to Handoff',
        'excerpt' => 'Integrating scene management, treatment, transport mode, and hospital radio report across an entire paramedic encounter.',
        'category' => 'Clinical Judgment',
        'accent' => 'ems',
        'keywords' => ['als scenario', 'simulation', 'handoff', 'transport', 'clinical judgment'],
        'sources' => [
            ['id' => 1, 'label' => 'NHTSA — Emergency Medical Services', 'url' => 'https://www.nhtsa.gov/emergency-medical-services'],
            ['id' => 2, 'label' => 'AHRQ — Patient Safety Network', 'url' => 'https://psnet.ahrq.gov/'],
        ],
        'sections' => [
            [
                'heading' => 'From dispatch information to scene size-up',
                'paragraphs' => [
                    'Full-call simulators begin with limited dispatch data—apply skepticism until scene confirms mechanism and patient count. NHTSA EMS education stresses crew resource management: driver prepares entry, lead paramedic forms impression, partner gathers equipment.<sup>1</sup> Scene safety, BSI, and primary survey precede detailed history when life threats are possible.',
                    'Early transport decisions balance on-scene stabilization time versus hospital capability. A penetrating trauma patient may need scoop-and-run; a STEMI may warrant 12-lead before lights-on transport when acquisition takes seconds. Branching choices should reflect realistic time costs.',
                ],
            ],
            [
                'heading' => 'Handoff and closure',
                'paragraphs' => [
                    'Hospital radio reports follow MIST or similar—mechanism, injuries/findings, signs, treatments, ETA. AHRQ handoff research shows structured reports reduce information loss.<sup>2</sup> Verbal handoff at bedside repeats critical items: allergies, medications given, last vitals, and outstanding tasks (second IV pending, pain reassessment due).',
                    'Post-call includes equipment restock, PCR completion, and debrief when outcomes were poor. Simulators test whether you update receiving facility when patient status changes en route—deterioration after initial “stable” report must trigger upgraded notification.',
                ],
            ],
        ],
    ],
    [
        'slug' => 'adaptive-nrp-readiness',
        'exercise_slug' => 'adaptive-nrp-readiness',
        'title' => 'Adaptive Paramedic Certification Readiness',
        'excerpt' => 'Strategies for choose-all-that-apply and multi-domain NRP-style items spanning cardiology, trauma, OB, and operations.',
        'category' => 'Exam Prep',
        'accent' => 'safety',
        'keywords' => ['nrp', 'certification', 'exam prep', 'multi-select', 'readiness'],
        'sources' => [
            ['id' => 1, 'label' => 'NHTSA — National EMS Scope of Practice Model', 'url' => 'https://www.nhtsa.gov/emergency-medical-services/emergency-medical-services-scope-of-practice-model'],
            ['id' => 2, 'label' => 'NIH MedlinePlus — Emergency Medical Services', 'url' => 'https://medlineplus.gov/ency/article/001928.htm'],
        ],
        'sections' => [
            [
                'heading' => 'Multi-select exam mechanics',
                'paragraphs' => [
                    'Adaptive readiness items often require selecting every correct option—not just the best single answer. Read each option independently: true statements about indications, contraindications, doses, and scope may coexist in one question. NHTSA’s national scope of practice model defines paramedic competencies across assessment, pharmacology, and operations—exam items cross those domains deliberately.<sup>1</sup>',
                    'Partial credit may not exist—one wrong selection fails the item. Eliminate definitively false options first (wrong dose, wrong route, BLS-only intervention listed as paramedic-only). When two options conflict, re-read stem for patient stability and scope context.',
                ],
            ],
            [
                'heading' => 'Cross-topic integration',
                'paragraphs' => [
                    'Certification-style drills blend pediatrics with toxicology, cardiology with electrolyte emergencies, and trauma with environmental exposure.<sup>2</sup> A question may pair correct 12-lead findings with correct medication choices and incorrect fluid volumes—test each domain separately before submitting.',
                    'Time management: flag lengthy multi-select items but avoid rushing single-answer cardiac algorithms buried in the same block. Review federal scope documents and local protocol differences before exam day—national tests reflect consensus paramedic practice, not one county’s optional skills.',
                ],
            ],
        ],
    ],
];
