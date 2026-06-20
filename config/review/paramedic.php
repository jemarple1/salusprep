<?php

return [
    [
        'slug' => 'pharmacology-principles',
        'title' => 'Paramedic Pharmacology: Mechanism, Safety & FDA Oversight',
        'excerpt' => 'How drugs work, how the FDA regulates them, and what that means for paramedic-level decision making.',
        'category' => 'Pharmacology',
        'accent' => 'pharma',
        'symbols' => '💊',
        'keywords' => ['pharmacology', 'fda', 'mechanism', 'contraindications', 'drugs'],
        'sources' => [
            ['id' => 1, 'label' => 'FDA — Drug Safety and Availability', 'url' => 'https://www.fda.gov/drugs/drug-safety-and-availability'],
            ['id' => 2, 'label' => 'NIH DailyMed — Drug labeling', 'url' => 'https://dailymed.nlm.nih.gov/dailymed/'],
        ],
        'sections' => [
            [
                'heading' => 'From molecule to patient effect',
                'paragraphs' => [
                    'Paramedics administer a broad formulary under medical direction. Understanding pharmacodynamics (what the drug does to the body) and pharmacokinetics (what the body does to the drug) explains onset, peak, duration, and interactions—not just memorized doses.',
                    'The FDA approves medications based on evidence of safety and efficacy and monitors adverse events after approval.<sup>1</sup> Field providers align with labeled indications and local protocols; off-label use requires explicit medical direction.',
                ],
            ],
            [
                'heading' => 'Reading labels and references',
                'paragraphs' => [
                    'NIH DailyMed publishes FDA-approved package inserts—concentrations, routes, boxed warnings, and pregnancy categories.<sup>2</sup> Before unfamiliar infusions or high-risk drugs, confirm compatibility, drip rates, and reversal agents.',
                    'Pediatric and geriatric patients alter dosing: weight-based calculations, reduced renal clearance, and polypharmacy increase adverse effect risk.',
                ],
            ],
            [
                'heading' => 'High-alert medications',
                'paragraphs' => [
                    'Sedatives, paralytics, vasopressors, and concentrated electrolytes demand double-checks, pump use when available, and continuous monitoring. Medication errors in EMS are underreported but preventable with standardized kits and read-back protocols.',
                    'Document every milligram and microgram. Your record is the legal and clinical source of truth.',
                ],
            ],
        ],
    ],
    [
        'slug' => 'twelve-lead-ecg',
        'title' => '12-Lead ECG Acquisition & STEMI Recognition',
        'excerpt' => 'Electrocardiographic fundamentals from NHLBI heart education and prehospital STEMI activation principles.',
        'category' => 'Cardiac',
        'accent' => 'rescue',
        'symbols' => '🫀',
        'keywords' => ['12-lead', 'ecg', 'stemi', 'acs', 'chest pain'],
        'sources' => [
            ['id' => 1, 'label' => 'NIH NHLBI — Heart Attack', 'url' => 'https://www.nhlbi.nih.gov/health/heart-attack'],
            ['id' => 2, 'label' => 'CDC — Heart Disease — Heart Attack', 'url' => 'https://www.cdc.gov/heart-disease/about/heart-attack.htm'],
        ],
        'sections' => [
            [
                'heading' => 'Why 12 leads in the field',
                'paragraphs' => [
                    'ST-elevation myocardial infarction (STEMI) is a time-critical diagnosis. NHLBI patient education materials emphasize that heart attacks require emergency care to restore blood flow.<sup>1</sup> Prehospital 12-lead ECGs shorten door-to-balloon times when transmitted or interpreted en route.',
                    'The CDC reports hundreds of thousands of heart attacks annually in the United States;<sup>2</sup> many present with atypical symptoms—especially in women, older adults, and people with diabetes.',
                ],
            ],
            [
                'heading' => 'Acquisition quality',
                'paragraphs' => [
                    'Proper electrode placement on clean, dry skin is non-negotiable. Acquire leads during quiet breathing when possible; repeat after nitroglycerin or pain relief to detect dynamic changes.',
                    'Compare to prior tracings when available. New left bundle branch block with symptoms may warrant STEMI pathway activation per protocol.',
                ],
            ],
            [
                'heading' => 'Activation and destination',
                'paragraphs' => [
                    'STEMI alerts bypass emergency department delays when systems support direct cath lab routing. Communicate rhythm, blood pressure, and contraindications to aspirin, heparin, or fibrinolytics clearly.',
                    'Non-ST elevation ACS still demands urgency—serial ECGs and high-risk feature assessment guide destination and early treatment.',
                ],
            ],
        ],
    ],
    [
        'slug' => 'advanced-cardiac-care',
        'title' => 'Advanced Cardiac Life Support Concepts in EMS',
        'excerpt' => 'Cardiac arrest physiology and resuscitation priorities informed by NIH and CDC cardiovascular guidance.',
        'category' => 'Cardiac',
        'accent' => 'ems',
        'symbols' => '⚡',
        'keywords' => ['acls', 'arrest', 'vasopressors', 'airway', 'rosc'],
        'sources' => [
            ['id' => 1, 'label' => 'CDC — About Cardiac Arrest', 'url' => 'https://www.cdc.gov/heart-disease/about/cardiac-arrest.htm'],
            ['id' => 2, 'label' => 'NIH NHLBI — CPR', 'url' => 'https://www.nhlbi.nih.gov/health/cpr'],
        ],
        'sections' => [
            [
                'heading' => 'The chain of survival',
                'paragraphs' => [
                    'Sudden cardiac arrest remains a major public health challenge.<sup>1</sup> Paramedics extend the chain with advanced airway management, IV/IO access, medication administration, and rhythm analysis beyond AED capabilities.',
                    'High-quality CPR remains the centerpiece—NHLBI stresses compressions and defibrillation as life-saving fundamentals.<sup>2</sup> Advanced airways should not interrupt compressions longer than necessary.',
                ],
            ],
            [
                'heading' => 'Rhythms and reversible causes',
                'paragraphs' => [
                    'Treat shockable rhythms (VF/pulseless VT) with defibrillation and epinephrine per protocol. PEA and asystole demand compression quality and search for reversible causes: hypoxia, hypovolemia, hydrogen ion (acidosis), hypo-/hyperkalemia, hypothermia, tension pneumothorax, tamponade, toxins, thrombosis.',
                    'Capnography provides real-time feedback: sudden rise may signal return of spontaneous circulation (ROSC); flat trace during compressions suggests poor placement or futility—interpret in clinical context.',
                ],
            ],
            [
                'heading' => 'Post-ROSC care',
                'paragraphs' => [
                    'After ROSC, avoid hyperventilation, support blood pressure, obtain 12-lead ECG, and consider targeted temperature management per protocol. Transport to appropriate receiving centers with early notification.',
                    'Family communication and scene debrief reduce psychological injury to bystanders and crews alike.',
                ],
            ],
        ],
    ],
    [
        'slug' => 'pediatric-assessment',
        'title' => 'Pediatric Assessment & Weight-Based Care',
        'excerpt' => 'Age-specific assessment approaches drawing on CDC child health data and NIH pediatric references.',
        'category' => 'Pediatrics',
        'accent' => 'medic',
        'symbols' => '👶',
        'keywords' => ['pediatric', 'children', 'broselow', 'assessment', 'family'],
        'sources' => [
            ['id' => 1, 'label' => 'CDC — Child Development', 'url' => 'https://www.cdc.gov/ncbddd/childdevelopment/index.html'],
            ['id' => 2, 'label' => 'NIH MedlinePlus — Pediatric health', 'url' => 'https://medlineplus.gov/childrenshealth.html'],
        ],
        'sections' => [
            [
                'heading' => 'Children are not small adults',
                'paragraphs' => [
                    'The CDC tracks developmental milestones and injury patterns across childhood—airway anatomy, compensatory shock physiology, and communication ability all change with age.<sup>1</sup> Vital sign normals vary; use length- or weight-based reference tools for dosing and equipment.',
                    'NIH consumer health resources highlight that children’s symptoms may be subtle—tachycardia and poor perfusion can precede hypotension.<sup>2</sup>',
                ],
            ],
            [
                'heading' => 'Assessment techniques',
                'paragraphs' => [
                    'Observe before touching when safe—the pediatric assessment triangle (appearance, work of breathing, circulation) guides first impressions. Involve caregivers for history: immunizations, medications, allergies, and baseline behavior.',
                    'Family-centered communication reduces fear. Explain procedures in simple terms; allow a parent to hold when it does not delay critical care.',
                ],
            ],
            [
                'heading' => 'Equipment and dosing',
                'paragraphs' => [
                    'Use pediatric-specific BVMs, airways, and energy doses for defibrillation. Broselow-style tapes or validated apps reduce calculation errors under stress.',
                    'Suspect non-accidental trauma when mechanism and injuries disagree—follow mandatory reporting laws in your state.',
                ],
            ],
        ],
    ],
    [
        'slug' => 'critical-care-transport',
        'title' => 'Critical Care Transport & EMS Systems',
        'excerpt' => 'Interfacility transport, specialty teams, and national EMS system coordination through NHTSA and HHS.',
        'category' => 'Systems',
        'accent' => 'safety',
        'symbols' => '🚑',
        'keywords' => ['transport', 'critical care', 'interfacility', 'helicopter', 'handoff'],
        'sources' => [
            ['id' => 1, 'label' => 'NHTSA — Emergency Medical Services', 'url' => 'https://www.nhtsa.gov/emergency-medical-services'],
            ['id' => 2, 'label' => 'HHS — Office of the Assistant Secretary for Preparedness and Response', 'url' => 'https://aspr.hhs.gov/'],
        ],
        'sections' => [
            [
                'heading' => 'Layers of EMS response',
                'paragraphs' => [
                    'NHTSA describes EMS as an integrated system—911 dispatch, first response, ambulance transport, and specialty resources including air medical.<sup>1</sup> Paramedics operate at the apex of prehospital care, often leading interfacility transfers of ventilated, infused, and multi-monitor patients.',
                    'Critical care transport may involve nurses or physicians; paramedics must communicate parity of interventions and anticipated deterioration en route.',
                ],
            ],
            [
                'heading' => 'Preparedness and surge',
                'paragraphs' => [
                    'HHS ASPR leads national health preparedness for disasters and public health emergencies.<sup>2</sup> Paramedics deploy in strike teams, mobile ICUs, and evacuation missions when routine transport networks fail.',
                    'Equipment checks, drug restocking, and crew rest are operational essentials—not optional when missions extend for days.',
                ],
            ],
            [
                'heading' => 'Handoff excellence',
                'paragraphs' => [
                    'Structured handoffs (SBAR, I-PASS) reduce information loss. Present airway status, drips with concentrations, recent vitals trend, and pending interventions.',
                    'The transport ends when the receiving team acknowledges understanding—not merely when the stretcher stops moving.',
                ],
            ],
        ],
    ],
];
