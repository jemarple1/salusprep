<?php

return [
    [
        'slug' => 'abc-prioritization',
        'exercise_slug' => 'abc-prioritization',
        'title' => 'ABC Prioritization for NCLEX-PN',
        'excerpt' => 'Selecting the first nursing action by addressing airway, breathing, and circulation threats before lesser urgent needs.',
        'category' => 'Prioritization',
        'accent' => 'rescue',
        'keywords' => ['abc', 'prioritization', 'airway', 'breathing', 'circulation', 'first action'],
        'sources' => [
            ['id' => 1, 'label' => 'NIH MedlinePlus — Breathing difficulty', 'url' => 'https://medlineplus.gov/breathingproblems.html'],
            ['id' => 2, 'label' => 'NIH MedlinePlus — Vital signs', 'url' => 'https://medlineplus.gov/ency/article/002341.htm'],
        ],
        'sections' => [
            [
                'heading' => 'Airway and breathing before paperwork',
                'paragraphs' => [
                    'NCLEX-PN prioritization items often list four plausible nursing actions—only one addresses the immediate physiologic threat. MedlinePlus describes breathing difficulty as a signal that oxygenation or ventilation is failing; airway obstruction, anaphylaxis, and pulmonary edema demand intervention before completing routine assessments on stable patients.<sup>1</sup>',
                    'A patient speaking in single words with SpO₂ 82% needs oxygen and positioning before blood glucose on a diabetic patient in the next room with stable vitals. Partial airway compromise from tongue swelling trumps IV antibiotic timing for a patient with cellulitis without fever. When airway is secure, move to breathing effort and circulation—treat chest pain with hypotension as perfusion failure, not anxiety.',
                ],
            ],
            [
                'heading' => 'Circulation and stable versus unstable',
                'paragraphs' => [
                    'Vital signs contextualize ABC decisions: tachycardia with hypotension and cool skin signals shock requiring fluid resuscitation or hemorrhage control notification before routine toileting assistance.<sup>2</sup> Stable hypertension without symptoms does not outrank a patient with new chest pain and diaphoresis.',
                    'When all patients appear stable, use secondary frameworks—acute change, expected complications, and time-sensitive treatments. ABC still applies: a postoperative patient with sudden shortness of breath may need assessment for pulmonary embolism or hemorrhage before pain medication for another patient’s chronic osteoarthritis.',
                ],
            ],
        ],
    ],
    [
        'slug' => 'maslow-prioritization',
        'exercise_slug' => 'maslow-prioritization',
        'title' => 'Maslow\'s Hierarchy in Nursing Prioritization',
        'excerpt' => 'Ranking patient needs from physiologic survival through safety when multiple non-ABC concerns compete for attention.',
        'category' => 'Prioritization',
        'accent' => 'safety',
        'keywords' => ['maslow', 'hierarchy', 'physiologic', 'safety', 'prioritization'],
        'sources' => [
            ['id' => 1, 'label' => 'HHS — Healthy People 2030', 'url' => 'https://health.gov/healthypeople'],
            ['id' => 2, 'label' => 'AHRQ — Patient Safety Network', 'url' => 'https://psnet.ahrq.gov/'],
        ],
        'sections' => [
            [
                'heading' => 'Physiologic needs dominate',
                'paragraphs' => [
                    'Maslow’s hierarchy places physiologic requirements—oxygen, fluids, nutrition, temperature, elimination—at the base. HHS Healthy People 2030 links basic physiologic stability to broader health outcomes; practical nurses address hypoglycemia, dehydration, and hypothermia before psychosocial interventions when both are unmet.<sup>1</sup>',
                    'A patient refusing dinner because of loneliness still needs insulin if blood glucose is 400 mg/dL. Rank hunger, thirst, and pain within physiologic tier by acuity: acute chest pain outranks delayed meal tray for a stable patient. Elimination urgency matters when urinary retention risks bladder overdistension or autonomic dysreflexia in spinal cord injury.',
                ],
            ],
            [
                'heading' => 'Safety and belonging after stability',
                'paragraphs' => [
                    'Once physiologic needs are secure, safety—fall risk, infection isolation, suicide precautions, and medication errors—takes priority over esteem or belonging needs.<sup>2</sup> Placing a confused patient with high Morse score in a bed near the nurses’ station outranks rearranging flowers for a stable long-term care resident.',
                    'Psychosocial needs become primary when physiologic and safety boxes are checked: therapeutic communication for grief, anxiety before elective procedures, or cultural support during end-of-life care. NCLEX distractors pair a social need with a hidden physiologic crisis—always scan for Maslow’s base first.',
                ],
            ],
        ],
    ],
    [
        'slug' => 'delegation',
        'exercise_slug' => 'delegation',
        'title' => 'Delegation and Supervision for LPN Practice',
        'excerpt' => 'What registered nurses may assign to LPN/LVN and unlicensed assistive personnel versus what requires RN retention.',
        'category' => 'Leadership',
        'accent' => 'medic',
        'keywords' => ['delegation', 'uap', 'rn', 'lpn', 'scope', 'supervision'],
        'sources' => [
            ['id' => 1, 'label' => 'AHRQ — Patient Safety Network', 'url' => 'https://psnet.ahrq.gov/'],
            ['id' => 2, 'label' => 'HHS — Health Information Privacy', 'url' => 'https://www.hhs.gov/hipaa/index.html'],
        ],
        'sections' => [
            [
                'heading' => 'Five rights of delegation',
                'paragraphs' => [
                    'Safe delegation requires the right task, circumstance, person, direction, and supervision. AHRQ patient safety literature ties communication failures during handoffs and task assignment to adverse events.<sup>1</sup> RNs delegate stable, predictable tasks with clear instructions; they retain unstable patients, new admissions with incomplete data, and tasks requiring nursing judgment beyond the delegatee’s scope.',
                    'UAP may obtain vital signs, assist with ambulation, bathe, and feed when policies allow. LPNs administer medications, provide wound care per competency, and reinforce teaching—but initial comprehensive assessment and new care plan development typically remain RN responsibilities in acute care. State nurse practice acts vary; exam items reflect general national patterns.',
                ],
            ],
            [
                'heading' => 'Tasks that cannot be delegated',
                'paragraphs' => [
                    'Never delegate sterile technique procedures to UAP unless facility policy explicitly permits and competency is verified. IV push medications, initial patient education on new diagnoses, triage decisions, and evaluation of unstable postoperative patients stay with licensed nurses at appropriate level.',
                    'The delegating RN remains accountable for patient outcomes—the delegate performs the task, but supervision must match risk.<sup>2</sup> After delegation, verify completion and assess the patient when results could signal deterioration: post-ambulation blood pressure after UAP walk, intake and output totals before diuretic timing. Document who was assigned what and follow-up findings.',
                ],
            ],
        ],
    ],
    [
        'slug' => 'therapeutic-communication',
        'exercise_slug' => 'therapeutic-communication',
        'title' => 'Therapeutic Communication Techniques',
        'excerpt' => 'Responses that validate feelings, encourage expression, and maintain boundaries without false reassurance or nurse-centered advice.',
        'category' => 'Communication',
        'accent' => 'ems',
        'keywords' => ['therapeutic communication', 'active listening', 'empathy', 'boundaries', 'nclex'],
        'sources' => [
            ['id' => 1, 'label' => 'NIH MedlinePlus — Talking with your child', 'url' => 'https://medlineplus.gov/ency/patientinstructions/000426.htm'],
            ['id' => 2, 'label' => 'HHS — Mental Health', 'url' => 'https://www.mentalhealth.gov/'],
        ],
        'sections' => [
            [
                'heading' => 'Techniques that open dialogue',
                'paragraphs' => [
                    'Open-ended questions (“Tell me more about what worries you”) invite patients to share; closed questions confirm facts. Reflection restates feelings—“You sound frightened about the surgery”—without judgment. Silence gives patients time to process; rushing to fill gaps shuts down disclosure.',
                    'MedlinePlus communication guidance for families emphasizes listening and honest, age-appropriate answers—skills that translate to adult patient encounters.<sup>1</sup> Clarifying and summarizing ensure understanding: “So you’ve had chest pain three times this week, mostly at rest?” Avoid leading questions that impose the nurse’s assumption.',
                ],
            ],
            [
                'heading' => 'What to avoid on exams and at bedside',
                'paragraphs' => [
                    'False reassurance (“Everything will be fine”) dismisses valid fear. Asking “Why” sounds accusatory. Changing the subject, giving personal opinions, or providing premature advice blocks therapeutic process. HHS mental health resources stress that validation does not require agreeing with distorted beliefs—acknowledge emotion while maintaining safety.<sup>2</sup>',
                    'Boundaries matter: sharing personal problems, excessive self-disclosure, or physical touch beyond culturally appropriate comfort violates professional therapeutic relationship. When patients express suicidal ideation, therapeutic communication shifts to direct safety assessment and protocol activation—not prolonged exploratory chat without escalation.',
                ],
            ],
        ],
    ],
    [
        'slug' => 'gcs-scoring',
        'exercise_slug' => 'gcs-scoring',
        'title' => 'Glasgow Coma Scale for LPN Neurologic Checks',
        'excerpt' => 'Scoring eye, verbal, and motor components during routine neurologic monitoring and when to escalate declining totals.',
        'category' => 'Assessment',
        'accent' => 'medic',
        'keywords' => ['gcs', 'glasgow', 'neurologic', 'assessment', 'mental status'],
        'sources' => [
            ['id' => 1, 'label' => 'CDC — Traumatic Brain Injury', 'url' => 'https://www.cdc.gov/traumaticbraininjury/'],
            ['id' => 2, 'label' => 'NIH MedlinePlus — Head injury', 'url' => 'https://medlineplus.gov/ency/article/000028.htm'],
        ],
        'sections' => [
            [
                'heading' => 'Accurate component scoring',
                'paragraphs' => [
                    'The Glasgow Coma Scale quantifies neurologic function for trauma, stroke, seizure, and metabolic encephalopathy monitoring. The CDC tracks traumatic brain injury as a major public health problem; early detection of declining consciousness reduces secondary injury risk.<sup>1</sup> LPNs document GCS per facility policy—often q15min during acute neurologic watch.',
                    'Score best eye response even if swelling limits one eye. Verbal component may be “E” intubated or “T” tracheostomy when speech is absent. Motor testing uses central pain stimulus when peripheral withdrawal is ambiguous—document localizing, withdrawal, decerebrate, or decorticate posturing separately from eye and verbal scores.',
                ],
            ],
            [
                'heading' => 'Trends and notification thresholds',
                'paragraphs' => [
                    'MedlinePlus lists confusion, vomiting, and unequal pupils as head injury warning signs requiring emergency care—a GCS drop of two or more points often triggers physician notification.<sup>2</sup> Compare to baseline: chronic dementia patients may have low verbal scores at baseline; acute change matters more than static low totals.',
                    'Sedating medications, hypoxia, hypotension, and hypoglycemia depress GCS reversibly—treat causes while notifying the RN. Document time, stimulus used, and pupil findings alongside GCS. Repeat after interventions to show whether neurologic status improved with correction of perfusion or glucose.',
                ],
            ],
        ],
    ],
    [
        'slug' => 'braden-scale',
        'exercise_slug' => 'braden-scale',
        'title' => 'Braden Scale for Pressure Injury Risk',
        'excerpt' => 'Rating sensory perception, moisture, activity, mobility, nutrition, and friction to guide prevention for at-risk patients.',
        'category' => 'Assessment',
        'accent' => 'safety',
        'keywords' => ['braden', 'pressure injury', 'skin', 'risk assessment', 'prevention'],
        'sources' => [
            ['id' => 1, 'label' => 'AHRQ — Pressure Injury Prevention', 'url' => 'https://www.ahrq.gov/patient-safety/settings/hospital/resource/pressureinjury/index.html'],
            ['id' => 2, 'label' => 'NIH MedlinePlus — Bedsores', 'url' => 'https://medlineplus.gov/ency/article/007069.htm'],
        ],
        'sections' => [
            [
                'heading' => 'Subscale interpretation',
                'paragraphs' => [
                    'The Braden Scale scores six subscales from 1 (severe deficit) to 3 or 4 (no impairment), with lower total scores indicating higher pressure injury risk. AHRQ hospital patient safety resources identify pressure injuries as preventable harm targeted by national quality programs.<sup>1</sup> Sensory perception reflects ability to feel discomfort—sedated, neuropathic, or cognitively impaired patients score lower.',
                    'Moisture accounts for perspiration, incontinence, and drain output on skin. Activity and mobility distinguish bedfast patients from those who walk occasionally. Nutrition reflects intake patterns and weight change—not single meal refusal. Friction and shear increase risk when sliding in bed or using dragging transfers without lift assistance.',
                ],
            ],
            [
                'heading' => 'From score to prevention',
                'paragraphs' => [
                    'MedlinePlus describes pressure injuries (bedsores) as skin breakdown from prolonged pressure—heels, sacrum, and hips are common sites.<sup>2</sup> Scores ≤18 typically prompt prevention bundles: reposition q2h, moisture barrier creams, heel elevation, nutrition consult, and specialty surfaces for highest risk.',
                    'Reassess Braden after clinical change—new immobility from fracture, ICU admission, or declining oral intake. LPNs implement turning schedules and document skin inspections; RNs revise care plans when scores drop. A stable Braden does not eliminate need for skin checks when patients have devices (CPAP straps, cervical collars) that create focal pressure.',
                ],
            ],
        ],
    ],
    [
        'slug' => 'morse-fall-scale',
        'exercise_slug' => 'morse-fall-scale',
        'title' => 'Morse Fall Scale Risk Assessment',
        'excerpt' => 'Scoring fall history, secondary diagnoses, ambulation aids, IV therapy, gait, and mental status to target fall precautions.',
        'category' => 'Assessment',
        'accent' => 'safety',
        'keywords' => ['morse', 'fall risk', 'safety', 'ambulation', 'precautions'],
        'sources' => [
            ['id' => 1, 'label' => 'CDC — STEADI — Older Adult Fall Prevention', 'url' => 'https://www.cdc.gov/falls/'],
            ['id' => 2, 'label' => 'AHRQ — Patient Safety Network', 'url' => 'https://psnet.ahrq.gov/'],
        ],
        'sections' => [
            [
                'heading' => 'Morse scoring elements',
                'paragraphs' => [
                    'The Morse Fall Scale assigns weighted points: history of falling, secondary diagnosis count, ambulatory aid use, IV or hep lock presence, gait quality, and mental status regarding own ability to transfer. The CDC STEADI initiative promotes systematic fall risk identification in older adults—tools like Morse operationalize that screening in hospitals.<sup>1</sup>',
                    'A patient who fell in the last year scores higher even if current injury is unrelated. Secondary diagnoses include anything in the chart—heart failure, CVA, orthostatic hypotension—not only admission diagnosis. Gait is observed: weak or impaired gait scores more than normal or bedrest/chairfast categories when gait is untested.',
                ],
            ],
            [
                'heading' => 'Interventions matched to risk',
                'paragraphs' => [
                    'Total Morse scores guide precaution levels: yellow wristband, bed alarms, hourly rounding, toileting schedules, non-skid footwear, and keeping call light within reach. High scores with impulsive mental status (“forgets limitations”) need closer supervision than high scores with normal cognition who use walkers reliably.<sup>2</sup>',
                    'Re-score after change in condition—new sedating medication, postoperative day zero mobility, or syncope episode. Falls are sentinel events; post-fall assessment includes vitals, neuro check, injury survey, and root-cause review. LPNs document Morse on admission and per policy, implementing precautions without waiting for physician orders when protocol allows standing fall bundles.',
                ],
            ],
        ],
    ],
];
