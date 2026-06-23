<?php

return [
    [
        'slug' => 'soap-charting',
        'exercise_slug' => 'soap-charting',
        'title' => 'SOAP Documentation for EMS Reports',
        'excerpt' => 'How structured Subjective, Objective, Assessment, and Plan charting supports continuity of care and medicolegal accountability.',
        'category' => 'Documentation',
        'accent' => 'ems',
        'keywords' => ['soap', 'documentation', 'charting', 'subjective', 'objective', 'assessment', 'plan'],
        'sources' => [
            ['id' => 1, 'label' => 'NHTSA — Emergency Medical Services', 'url' => 'https://www.nhtsa.gov/emergency-medical-services'],
            ['id' => 2, 'label' => 'AHRQ — Patient Safety Network', 'url' => 'https://psnet.ahrq.gov/'],
        ],
        'sections' => [
            [
                'heading' => 'What belongs in each SOAP section',
                'paragraphs' => [
                    'National EMS quality initiatives emphasize complete, legible patient care reports that survive handoff to hospital staff and retrospective review.<sup>1</sup> Subjective data captures what the patient or bystanders report—chief complaint, symptoms, allergies, medications, and events leading to the call. Objective data is what you measure and observe: vital signs, physical exam findings, Glasgow Coma Scale, and intervention times.',
                    'Assessment is your working clinical impression—not a hospital diagnosis. Plan documents treatments performed, oxygen and medication details, transport priority, and receiving facility notifications. Mixing sections (for example, vital signs under Subjective) weakens the narrative and confuses downstream providers.',
                ],
            ],
            [
                'heading' => 'Discarding noise and closing the loop',
                'paragraphs' => [
                    'Not every statement belongs in a PCR. Irrelevant social chatter, duplicated information, and non-clinical opinions clutter the record without improving care. The Agency for Healthcare Research and Quality links clear documentation to safer transitions and fewer communication failures.<sup>2</sup> Train yourself to filter noise while preserving clinically meaningful context.',
                    'Reassessment findings belong in Objective or Plan with timestamps. A SOAP note that ends at initial assessment misses the story of whether interventions worked—exactly what reviewers and QA committees examine after difficult calls.',
                ],
            ],
        ],
    ],
    [
        'slug' => 'triage-start',
        'exercise_slug' => 'triage-start',
        'title' => 'START Triage for Mass-Casualty Incidents',
        'excerpt' => 'The Simple Triage and Rapid Treatment algorithm for sorting adult trauma patients when demand exceeds transport resources.',
        'category' => 'Triage',
        'accent' => 'rescue',
        'keywords' => ['start', 'triage', 'mci', 'immediate', 'delayed', 'minor', 'expectant'],
        'sources' => [
            ['id' => 1, 'label' => 'CDC — Guidelines for Field Triage of Injured Patients', 'url' => 'https://www.cdc.gov/fieldtriage/'],
            ['id' => 2, 'label' => 'NHTSA — Emergency Medical Services', 'url' => 'https://www.nhtsa.gov/emergency-medical-services'],
        ],
        'sections' => [
            [
                'heading' => 'START decision steps',
                'paragraphs' => [
                    'When multiple patients need care simultaneously, triage identifies who will die without immediate intervention versus who can wait. The CDC’s field triage framework—originally oriented toward single-patient trauma center decisions—shares the same physiology-first mindset applied at mass-casualty scale.<sup>1</sup> START begins by directing ambulatory patients to a minor (green) collection area, then evaluates the remainder in place.',
                    'For non-ambulatory patients, assess respirations first. Apneic patients may receive repositioning once; if breathing does not start, tag expectant (black) when resources are critically limited. Respiratory rate over 30 tags immediate (red). Perfusion is checked via capillary refill or radial pulse—delayed refill or absent radial pulse tags immediate. Mental status inability to follow simple commands also tags immediate; otherwise delayed (yellow).',
                ],
            ],
            [
                'heading' => 'Tags, transport, and limitations',
                'paragraphs' => [
                    'START is a sorting tool, not definitive treatment. Immediate patients receive lifesaving interventions on scene only when they can be done in seconds—opening airways, controlling catastrophic hemorrhage—before rapid transport. Delayed patients are monitored while immediate patients move; minor patients may assist with logistics under supervision.',
                    'Federal EMS education emphasizes that MCI triage protocols must be practiced before they are needed.<sup>2</sup> START does not replace pediatric triage (use JumpSTART), does not account for blast lung or complex medical complaints well, and must be adapted to local incident command structures.',
                ],
            ],
        ],
    ],
    [
        'slug' => 'triage-jumpstart',
        'exercise_slug' => 'triage-jumpstart',
        'title' => 'JumpSTART Pediatric MCI Triage',
        'excerpt' => 'Pediatric-specific triage modifications for children in mass-casualty events when adult START criteria do not apply.',
        'category' => 'Triage',
        'accent' => 'rescue',
        'keywords' => ['jumpstart', 'pediatric', 'triage', 'mci', 'children', 'respiratory'],
        'sources' => [
            ['id' => 1, 'label' => 'CDC — Child Safety and Injury Prevention', 'url' => 'https://www.cdc.gov/child-safety/'],
            ['id' => 2, 'label' => 'NIH MedlinePlus — Emergency Medical Services', 'url' => 'https://medlineplus.gov/ency/article/001928.htm'],
        ],
        'sections' => [
            [
                'heading' => 'Why pediatric triage differs',
                'paragraphs' => [
                    'Children have higher baseline respiratory rates, smaller blood volumes, and different behavioral responses to stress than adults. The CDC’s child injury prevention resources stress that trauma systems must account for age-specific physiology when allocating scarce resources.<sup>1</sup> JumpSTART adapts START for patients roughly 1 year through adolescence, with infant-specific algorithms handled separately in many jurisdictions.',
                    'Ambulatory children are sorted to minor, as in START. Apneic children receive five rescue breaths first—pediatric airways often obstruct from positioning or reversible causes. If apnea persists after repositioning and breaths, expectant tagging may apply under extreme resource scarcity; if breathing resumes, reassess using perfusion and mental status criteria.',
                ],
            ],
            [
                'heading' => 'Applying JumpSTART on scene',
                'paragraphs' => [
                    'Respiratory rate thresholds differ from adult START: rates over 45 or under 10 in children not crying tag immediate. Capillary refill over two seconds, absent distal pulses, or cool extremities signal immediate perfusion failure. Mental status is evaluated with the AVPU scale—unresponsive or responds only to pain tags immediate; verbal or alert responses support delayed tagging when other steps are normal.',
                    'EMS providers should document triage tag, time, and location assigned for reunification and family support.<sup>2</sup> Emotional caregivers and missing guardians are common at pediatric MCIs; triage officers coordinate with incident command for family zones separate from treatment areas.',
                ],
            ],
        ],
    ],
    [
        'slug' => 'triage-salt',
        'exercise_slug' => 'triage-salt',
        'title' => 'SALT Triage: Sort, Assess, Lifesaving, Treatment',
        'excerpt' => 'The SALT mass-gathering triage model that integrates lifesaving interventions before final transport priority assignment.',
        'category' => 'Triage',
        'accent' => 'rescue',
        'keywords' => ['salt', 'triage', 'sort', 'assess', 'lifesaving', 'transport', 'mci'],
        'sources' => [
            ['id' => 1, 'label' => 'CDC — Guidelines for Field Triage of Injured Patients', 'url' => 'https://www.cdc.gov/fieldtriage/'],
            ['id' => 2, 'label' => 'NHTSA — Emergency Medical Services', 'url' => 'https://www.nhtsa.gov/emergency-medical-services'],
        ],
        'sections' => [
            [
                'heading' => 'The four SALT phases',
                'paragraphs' => [
                    'SALT—Sort, Assess, Lifesaving interventions, Treatment and transport—was designed for events where patients may still be arriving and hazards are ongoing. Sort begins with global scene assessment: identify patients who are obviously dead, those with minor injuries who can walk, and those who need individual evaluation.<sup>1</sup> This global pass prevents bottlenecks at a single triage officer.',
                    'Assess moves to individual evaluation of non-walking patients. Lifesaving interventions occur during assessment when they can be performed in seconds—hemorrhage control, airway positioning, chest decompression per scope—not prolonged field surgery. Treatment and transport assigns final priority: immediate, delayed, minimal, or expectant.',
                ],
            ],
            [
                'heading' => 'SALT versus START in practice',
                'paragraphs' => [
                    'Unlike START’s rigid sequence for every patient, SALT explicitly allows simultaneous sorting waves as new victims arrive—critical for explosions, structural collapse, or ongoing threats. National EMS planning documents encourage agencies to pre-select and drill one primary MCI triage system rather than mixing incompatible tags on the same incident.<sup>2</sup>',
                    'Documentation includes SALT category, interventions performed during the lifesaving phase, and reassessment after interventions. A patient tagged delayed who deteriorates during treatment must be retriaged immediate without waiting for transport completion.',
                ],
            ],
        ],
    ],
    [
        'slug' => 'triage-mci',
        'exercise_slug' => 'triage-mci',
        'title' => 'Transport Priority in Multi-Patient Incidents',
        'excerpt' => 'How to allocate limited ambulances among triaged patients when several compete for the next transport resource.',
        'category' => 'Triage',
        'accent' => 'rescue',
        'keywords' => ['mci', 'transport', 'priority', 'resources', 'immediate', 'triage officer'],
        'sources' => [
            ['id' => 1, 'label' => 'NHTSA — Emergency Medical Services', 'url' => 'https://www.nhtsa.gov/emergency-medical-services'],
            ['id' => 2, 'label' => 'CDC — Guidelines for Field Triage of Injured Patients', 'url' => 'https://www.cdc.gov/fieldtriage/'],
        ],
        'sections' => [
            [
                'heading' => 'Who goes first when ambulances are scarce',
                'paragraphs' => [
                    'Mass-casualty incidents create a transport queue problem: multiple immediate patients, finite ambulances, and hospitals that may also surge. Incident command assigns transport officers who match vehicle capabilities to patient needs—advanced life support for critical airways, multiple ambulances for expectant or minimal patients only when resources allow.<sup>1</sup>',
                    'Among immediate (red) patients, priority often goes to those with reversible life threats who will die without transport—tension pneumothorax after decompression, severe hemorrhage after tourniquet, airway compromise after positioning. The CDC emphasizes abnormal physiology as a trigger for rapid definitive care even outside formal MCI triage.<sup>2</sup>',
                ],
            ],
            [
                'heading' => 'Maintaining scene integrity while moving patients',
                'paragraphs' => [
                    'Loading the wrong patient first can strand a more critical victim without monitoring. Triage tags, colored tarps, and geographic staging (red/yellow/green zones) keep priorities visible as crews rotate. Reassess delayed patients each time a unit becomes available—shock and airway failure can develop quietly in the yellow zone.',
                    'Communicate expected transport intervals to the triage officer: “next unit in four minutes” changes whether a borderline patient stays for another intervention or moves now. Clear radio traffic prevents duplicate requests and ambulance stacking at one hospital while another has capacity.',
                ],
            ],
        ],
    ],
    [
        'slug' => 'gcs-scenarios',
        'exercise_slug' => 'gcs-scenarios',
        'title' => 'Glasgow Coma Scale in Head Injury Assessment',
        'excerpt' => 'Scoring eye, verbal, and motor responses to quantify neurologic status and guide trauma transport decisions.',
        'category' => 'Assessment',
        'accent' => 'medic',
        'keywords' => ['gcs', 'glasgow', 'head injury', 'neurologic', 'trauma', 'mental status'],
        'sources' => [
            ['id' => 1, 'label' => 'CDC — Traumatic Brain Injury', 'url' => 'https://www.cdc.gov/traumaticbraininjury/'],
            ['id' => 2, 'label' => 'NIH MedlinePlus — Head injury', 'url' => 'https://medlineplus.gov/ency/article/000028.htm'],
        ],
        'sections' => [
            [
                'heading' => 'Components and scoring',
                'paragraphs' => [
                    'The Glasgow Coma Scale sums eye opening (1–4), verbal response (1–5), and motor response (1–6) for a total between 3 and 15. The CDC identifies traumatic brain injury as a major cause of death and disability; GCS gives EMS a reproducible snapshot at scene and during reassessment.<sup>1</sup> Score each component separately—do not default to “GCS 15” without testing all three domains.',
                    'Eye opening ranges from none to spontaneous. Verbal scores account for intubation (“E” notation) and language barriers where applicable. Motor response distinguishes purposeful movement, withdrawal from pain, abnormal flexion or extension, and none. Bilateral assessment matters: unequal pupils or lateralizing motor deficits suggest focal brain injury beyond the total score.',
                ],
            ],
            [
                'heading' => 'GCS in transport and triage decisions',
                'paragraphs' => [
                    'MedlinePlus notes that head injury severity correlates with altered consciousness, vomiting, and amnesia—GCS helps quantify the first of those findings.<sup>2</sup> Field triage guidelines use GCS thresholds (often ≤13) as trauma center criteria alongside physiology and mechanism. A dropping GCS during transport is more alarming than a stable low score at first contact.',
                    'Document GCS with time stamps after interventions—sedation, pain control, and hypoxia all depress scores. Repeat scoring every five minutes in unstable head injury or when coexisting shock or hypoglycemia could mimic neurologic decline.',
                ],
            ],
        ],
    ],
    [
        'slug' => 'burn-scoring',
        'exercise_slug' => 'burn-scoring',
        'title' => 'Burn TBSA Estimation and Severity',
        'excerpt' => 'Using body surface area rules to estimate burn extent and identify patients who need burn center or critical care resources.',
        'category' => 'Assessment',
        'accent' => 'safety',
        'keywords' => ['burn', 'tbsa', 'rule of nines', 'thermal', 'fluid resuscitation'],
        'sources' => [
            ['id' => 1, 'label' => 'CDC — Burns', 'url' => 'https://www.cdc.gov/burns/'],
            ['id' => 2, 'label' => 'NIH MedlinePlus — Burns', 'url' => 'https://medlineplus.gov/ency/article/000030.htm'],
        ],
        'sections' => [
            [
                'heading' => 'Rule of nines and depth',
                'paragraphs' => [
                    'Total body surface area (TBSA) burned drives fluid resuscitation formulas and burn center referral. The rule of nines assigns 9% to each arm, 18% to each leg, 18% to the chest, 18% to the back, 9% to the head (modified for children), and 1% to the perineum. Only partial-thickness and full-thickness burns count—simple erythema without blistering is excluded.<sup>1</sup>',
                    'MedlinePlus describes depth categories: superficial (red, painful), partial-thickness (blisters, severe pain), and full-thickness (leathery, may be insensate).<sup>2</sup> Circumferential burns threaten circulation and ventilation when eschar forms; document location (face, hands, joints) even when TBSA is small.',
                ],
            ],
            [
                'heading' => 'When burns become critical',
                'paragraphs' => [
                    'Burn center criteria commonly include TBSA greater than 20% in adults, higher-risk zones (face, hands, feet, genitals, major joints), inhalation injury, electrical or chemical mechanism, and burns with concurrent trauma. The CDC burn prevention program emphasizes that inhalation injury can exist without external flame burns—singed nasal hair, soot in mouth, and stridor are warning signs.<sup>1</sup>',
                    'Cover burns with clean dry dressings, prevent hypothermia, and provide high-flow oxygen when inhalation is suspected. EMT-Basic scope focuses on rapid transport, airway support, and pain management per protocol rather than field fluid calculations reserved for advanced providers.',
                ],
            ],
        ],
    ],
    [
        'slug' => 'stroke-scale',
        'exercise_slug' => 'stroke-scale',
        'title' => 'Stroke Recognition and EMS Action',
        'excerpt' => 'FAST screening, time-sensitive transport, and prehospital actions that preserve eligibility for hospital stroke interventions.',
        'category' => 'Assessment',
        'accent' => 'medic',
        'keywords' => ['stroke', 'fast', 'face', 'arms', 'speech', 'time', 'cva'],
        'sources' => [
            ['id' => 1, 'label' => 'CDC — Stroke', 'url' => 'https://www.cdc.gov/stroke/'],
            ['id' => 2, 'label' => 'NIH MedlinePlus — Stroke', 'url' => 'https://medlineplus.gov/stroke.html'],
        ],
        'sections' => [
            [
                'heading' => 'FAST and expanded prehospital screening',
                'paragraphs' => [
                    'Stroke is a leading cause of serious long-term disability; the CDC urges immediate EMS activation when symptoms appear because treatment windows are narrow.<sup>1</sup> FAST evaluates Face droop, Arm weakness, Speech difficulty, and Time to call EMS. Many systems add balance and vision checks (BE-FAST) for posterior circulation strokes missed by arm drift alone.',
                    'Last known well time—not symptom discovery time—anchors hospital thrombolysis and thrombectomy decisions. Ask bystanders when the patient was last normal; vague “woke up with it” histories may disqualify intervention. Document glucose early: hypoglycemia mimics stroke and is reversible without stroke center diversion.',
                ],
            ],
            [
                'heading' => 'Transport and on-scene priorities',
                'paragraphs' => [
                    'MedlinePlus lists sudden numbness, confusion, trouble walking, and severe headache as stroke warning signs requiring emergency care.<sup>2</sup> Prehospital priorities: airway and breathing support, position of comfort, oxygen if hypoxic, IV per local scope, and direct transport to the highest appropriate stroke center—not a lower-acuity facility for “stabilization” unless protocol mandates.',
                    'Avoid delaying transport for detailed secondary exams when FAST is positive. Notify receiving facilities with ETA and findings; blood pressure management in the field follows protocol—aggressive lowering is not routinely indicated prehospital. Seizure activity may require benzodiazepines per scope before transport continues.',
                ],
            ],
        ],
    ],
    [
        'slug' => 'vital-signs',
        'exercise_slug' => 'vital-signs',
        'title' => 'Vital Sign Interpretation at the EMT-Basic Level',
        'excerpt' => 'Reading blood pressure, pulse, respirations, and SpO₂ together to identify perfusion failure and guide first interventions.',
        'category' => 'Assessment',
        'accent' => 'ems',
        'keywords' => ['vital signs', 'blood pressure', 'pulse', 'respiratory rate', 'spo2', 'perfusion'],
        'sources' => [
            ['id' => 1, 'label' => 'NIH MedlinePlus — Vital signs', 'url' => 'https://medlineplus.gov/ency/article/002341.htm'],
            ['id' => 2, 'label' => 'CDC — High Blood Pressure', 'url' => 'https://www.cdc.gov/bloodpressure/'],
        ],
        'sections' => [
            [
                'heading' => 'Trends matter more than single numbers',
                'paragraphs' => [
                    'MedlinePlus defines vital signs as temperature, pulse, respiration rate, and blood pressure—measures of basic body function.<sup>1</sup> EMS adds oxygen saturation and pain scores in many systems. A single hypertensive reading means little in isolation; tachycardia with cool skin and narrow pulse pressure signals shock even before hypotension appears.',
                    'Respiratory rate is often the earliest vital to change in sepsis, pulmonary embolism, and metabolic crisis. Count for a full minute when rhythm is irregular. Pulse oximetry supplements but does not replace exam—carbon monoxide poisoning and poor perfusion can produce misleading readings.',
                ],
            ],
            [
                'heading' => 'Matching interventions to vital patterns',
                'paragraphs' => [
                    'The CDC notes that chronic hypertension is common; treat acute symptoms and end-organ dysfunction rather than arbitrary numeric targets in the field.<sup>2</sup> Bradycardia with hypotension may indicate conduction block or late shock; tachycardia with fever suggests sepsis; bradycardia with altered mental status may be opioid toxicity.',
                    'Reassess vitals after every intervention—oxygen, fluid bolus per scope, naloxone, bronchodilator assist. Document trends in the PCR: “BP 90/60 after 2 L O₂ via NRB, HR decreased from 130 to 110.” Patterns drive exam items and real-world QA review.',
                ],
            ],
        ],
    ],
    [
        'slug' => 'pharma-contraindications',
        'exercise_slug' => 'pharma-contraindications',
        'title' => 'EMT-Basic Medication Contraindications',
        'excerpt' => 'When protocol medications must be withheld because patient factors create unacceptable risk at the basic level.',
        'category' => 'Pharmacology',
        'accent' => 'pharma',
        'keywords' => ['contraindications', 'medications', 'allergy', 'protocol', 'withhold'],
        'sources' => [
            ['id' => 1, 'label' => 'FDA — Drug Safety and Availability', 'url' => 'https://www.fda.gov/drugs/drug-safety-and-availability'],
            ['id' => 2, 'label' => 'NIH MedlinePlus — Drug Information', 'url' => 'https://medlineplus.gov/druginformation.html'],
        ],
        'sections' => [
            [
                'heading' => 'Absolute versus relative contraindications',
                'paragraphs' => [
                    'The FDA requires labeling that describes when drugs should not be used or require special caution.<sup>1</sup> Absolute contraindications mean do not give—known anaphylaxis to a medication, nitroglycerin in suspected right ventricular infarction or severe hypotension per protocol, epinephrine auto-injector only when patient has no conflicting absolute rules. Relative contraindications require medical direction or protocol nuance: asthma history with beta-blocker allergy cross-reactivity questions, for example.',
                    'MedlinePlus consumer drug pages list common interactions and warnings EMS must connect to scene findings.<sup>2</sup> “Allergy to sulfa” does not always contraindicate every sulfa-derived drug, but protocol and standing orders define what EMT-Basics can parse without physician consultation.',
                ],
            ],
            [
                'heading' => 'Scene clues that trigger withholding',
                'paragraphs' => [
                    'Suspected stroke within nitroglycerin protocol limits, ingestion of unknown pills before glucose is checked, cocaine-associated chest pain where beta-blockade may be restricted, and pediatric patients where adult auto-injector doses exceed protocol weight bands—all are classic exam contraindication scenarios. When contraindicated, document reason and alternative care: positioning, oxygen, rapid transport.',
                    'Never withhold lifesaving medications due to incomplete history when protocol allows empiric treatment—anaphylaxis with unknown allergy history still receives epinephrine. Contraindication drills train recognition speed; wrong-route and wrong-patient errors often follow rushed “yes” answers without reading the full prompt.',
                ],
            ],
        ],
    ],
    [
        'slug' => 'pharma-assist',
        'exercise_slug' => 'pharma-assist',
        'title' => 'Assisting Patients With Prescribed Medications',
        'excerpt' => 'When EMT-Basics may help patients self-administer prescribed or protocol-authorized medications versus when to withhold and transport.',
        'category' => 'Pharmacology',
        'accent' => 'pharma',
        'keywords' => ['assist', 'prescribed', 'patient medication', 'bronchodilator', 'epinephrine'],
        'sources' => [
            ['id' => 1, 'label' => 'FDA — Safe Use of Medicines', 'url' => 'https://www.fda.gov/consumers/consumer-updates/safe-use-medicines'],
            ['id' => 2, 'label' => 'NIH MedlinePlus — Taking medicines', 'url' => 'https://medlineplus.gov/ency/patientinstructions/000534.htm'],
        ],
        'sections' => [
            [
                'heading' => 'Assist versus administer',
                'paragraphs' => [
                    'EMT-Basic scope often permits assisting with patient-owned bronchodilators, epinephrine auto-injectors, and nitroglycerin when the patient cannot physically deliver the dose but requests help and protocol criteria are met. The FDA emphasizes using medications only as directed—EMS assistance must stay within the patient’s prescribed intent, not independent prescribing.<sup>1</sup>',
                    'Assist means handing the device, helping position, or triggering an auto-injector after confirming identity and indication. Administering from the ambulance stock without standing orders is ALS or medical-direction territory in most states. If the patient is unconscious, assist with their own epinephrine may still be appropriate for anaphylaxis when product is available.',
                ],
            ],
            [
                'heading' => 'When to withhold and transport',
                'paragraphs' => [
                    'MedlinePlus advises patients to know why each medicine is taken and to report side effects—EMS extends that vigilance when patients are confused or hypotensive.<sup>2</sup> Withhold nitroglycerin when systolic pressure is below protocol threshold, when phosphodiesterase inhibitor use is reported, or when right ventricular infarction is suspected. Withhold bronchodilator assist when the patient has no history of prescribed inhaler use unless protocol authorizes emergency bronchodilator administration from supply.',
                    'After assist, reassess vitals and symptoms. Document drug name, dose, route, time, and patient response. Multiple doses follow protocol intervals—not patient demand alone. Transport remains indicated when distress persists after appropriate assisted doses.',
                ],
            ],
        ],
    ],
    [
        'slug' => 'pharma-matching',
        'exercise_slug' => 'pharma-matching',
        'title' => 'Matching Presentations to EMT-Basic Protocol Drugs',
        'excerpt' => 'Linking chief complaint patterns to the correct basic-level medication while respecting allergies and protocol limits.',
        'category' => 'Pharmacology',
        'accent' => 'pharma',
        'keywords' => ['matching', 'protocol', 'epinephrine', 'nitroglycerin', 'albuterol', 'glucose'],
        'sources' => [
            ['id' => 1, 'label' => 'NIH MedlinePlus — Drug Information', 'url' => 'https://medlineplus.gov/druginformation.html'],
            ['id' => 2, 'label' => 'FDA — Index to Drug-Specific Information', 'url' => 'https://www.fda.gov/drugs/drug-approvals-and-databases/index-drug-specific-information'],
        ],
        'sections' => [
            [
                'heading' => 'Classic presentation pairings',
                'paragraphs' => [
                    'Anaphylaxis with urticaria, angioedema, and bronchospasm pairs with epinephrine intramuscular per protocol. Chest pain with suspected cardiac ischemia and adequate blood pressure pairs with nitroglycerin when contraindications are absent. Bronchospasm with wheezing and history of asthma or COPD pairs with bronchodilator therapy—patient assist or protocol administration depending on jurisdiction.',
                    'Altered mental status with documented hypoglycemia or low glucometer reading pairs with oral glucose when awake and able to swallow, or glucose paste/gel per protocol.<sup>1</sup> Opioid respiratory depression with pinpoint pupils pairs with naloxone where EMT-Basics carry it. Matching drills test whether you recognize the primary problem before defaulting to a familiar drug.',
                ],
            ],
            [
                'heading' => 'Avoiding distractor medications',
                'paragraphs' => [
                    'FDA drug labeling describes indications—not every wheezing patient needs epinephrine; not every tachycardia needs adenosine at basic level.<sup>2</sup> Exam distractors include giving nitroglycerin for pulmonary edema when CPAP and ALS are needed, or aspirin when allergy exists. Pain control medications at EMT-Basic scope are limited—nitroglycerin treats ischemic pain mechanistically but is not analgesia for renal colic.',
                    'When two drugs seem plausible, revisit ABCs and vitals. Syncope from vasovagal episode needs positioning and oxygen, not epinephrine. Hyperventilation from anxiety may improve with coaching without any drug. The best match respects both presentation and scope.',
                ],
            ],
        ],
    ],
    [
        'slug' => 'pharma-outcomes',
        'exercise_slug' => 'pharma-outcomes',
        'title' => 'Evaluating Medication Effectiveness in the Field',
        'excerpt' => 'Identifying objective improvement after EMT-Basic medications and recognizing when reassessment demands escalation.',
        'category' => 'Pharmacology',
        'accent' => 'pharma',
        'keywords' => ['outcomes', 'reassessment', 'therapeutic effect', 'epinephrine', 'naloxone'],
        'sources' => [
            ['id' => 1, 'label' => 'FDA — Drug Development', 'url' => 'https://www.fda.gov/drugs/development-approval-process-drugs'],
            ['id' => 2, 'label' => 'NIH MedlinePlus — Medication errors', 'url' => 'https://medlineplus.gov/ency/patientinstructions/000618.htm'],
        ],
        'sections' => [
            [
                'heading' => 'Objective endpoints by drug class',
                'paragraphs' => [
                    'Effective epinephrine for anaphylaxis shows rising blood pressure, decreased wheezing, reduced angioedema, and improved mental status within minutes—not merely a resolved rash while stridor persists. Bronchodilators improve audible wheeze, work of breathing, and speaking ability; peak flow is rarely available in EMS but respiratory rate and SpO₂ trends matter.',
                    'Naloxone should restore respiratory effort and alertness without necessarily normalizing all vitals immediately.<sup>1</sup> Nitroglycerin may decrease blood pressure modestly with chest pain reduction—confusing hypotension from overdose versus therapeutic effect requires exam and repeat vitals. Oral glucose raises blood glucose and improves cognition when hypoglycemia was the cause.',
                ],
            ],
            [
                'heading' => 'When improvement is absent',
                'paragraphs' => [
                    'MedlinePlus warns that medicines can fail when wrong drug, wrong dose, or wrong indication is selected.<sup>2</sup> Lack of expected improvement after correct dosing triggers protocol next steps: second epinephrine dose for anaphylaxis, additional bronchodilator per interval, ALS intercept, or rapid transport with continued airway support.',
                    'Document partial response explicitly: “Wheezing improved, angioedema persistent.” Partial responses guide hospital alerts. Do not declare success and downgrade transport when critical symptoms remain. Time-stamped reassessment proves standard-of-care in post-incident review.',
                ],
            ],
        ],
    ],
    [
        'slug' => 'pharma-dosage',
        'exercise_slug' => 'pharma-dosage',
        'title' => 'EMT-Basic Medication Doses and Routes',
        'excerpt' => 'Standard protocol dosing for common basic-level medications—memorization anchored to FDA labeling and local standing orders.',
        'category' => 'Pharmacology',
        'accent' => 'pharma',
        'keywords' => ['dosage', 'dose', 'route', 'epinephrine', 'nitroglycerin', 'naloxone'],
        'sources' => [
            ['id' => 1, 'label' => 'FDA — Index to Drug-Specific Information', 'url' => 'https://www.fda.gov/drugs/drug-approvals-and-databases/index-drug-specific-information'],
            ['id' => 2, 'label' => 'NIH DailyMed — FDA drug labels', 'url' => 'https://dailymed.nlm.nih.gov/dailymed/'],
        ],
        'sections' => [
            [
                'heading' => 'High-frequency EMT-Basic doses',
                'paragraphs' => [
                    'Epinephrine for anaphylaxis is commonly 0.3 mg (0.3 mL of 1:1,000) IM in the lateral thigh for adults, with pediatric weight-based auto-injector or drawn doses per protocol. Adult IM epinephrine may repeat at 5–15 minute intervals per standing orders when anaphylaxis persists.<sup>1</sup> Auto-injectors deliver fixed doses—0.15 mg and 0.3 mg pediatric/adult products must match patient size.',
                    'Nitroglycerin sublingual tablets or spray typically administer 0.4 mg per dose with protocol-limited repeats after blood pressure checks. Naloxone intranasal or IM doses vary by formulation—know whether your agency uses 2 mg IN, 0.4 mg IM, or concentrated products requiring volume conversion. Albuterol metered-dose inhaler assists often specify 2.5 mg via spacer per puff count protocol.',
                ],
            ],
            [
                'heading' => 'Routes and calculation discipline',
                'paragraphs' => [
                    'DailyMed hosts FDA-approved labels with concentration, route, and maximum daily dose data EMS protocols simplify for emergency single doses.<sup>2</sup> Intramuscular epinephrine for anaphylaxis is not the same concentration pathway as intravenous epinephrine in cardiac arrest—route and concentration errors are high-mortality mistakes on exams and in practice.',
                    'Weight-based pediatric dosing requires actual or Broselow length-based weight when scale unavailable. Double-check units: milligrams versus milliliters, micrograms versus milligrams. Document exact product, dose, route, site, and time. When medical direction orders a non-standard dose, record physician name and order on the PCR.',
                ],
            ],
        ],
    ],
];
