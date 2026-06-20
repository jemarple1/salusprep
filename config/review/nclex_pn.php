<?php

return [
    [
        'slug' => 'nursing-process-adpie',
        'title' => 'The Nursing Process: Assessment Through Evaluation',
        'excerpt' => 'How systematic ADPIE thinking supports safe practical nursing care, aligned with HHS quality initiatives.',
        'category' => 'Fundamentals',
        'accent' => 'medic',
        'symbols' => '🩺',
        'keywords' => ['adpie', 'nursing process', 'assessment', 'planning', 'evaluation'],
        'sources' => [
            ['id' => 1, 'label' => 'AHRQ — Patient Safety Network', 'url' => 'https://psnet.ahrq.gov/'],
            ['id' => 2, 'label' => 'HHS — Healthy People 2030', 'url' => 'https://health.gov/healthypeople'],
        ],
        'sections' => [
            [
                'heading' => 'ADPIE as clinical reasoning',
                'paragraphs' => [
                    'Licensed practical nurses apply the nursing process daily: Assess, Diagnose (nursing diagnoses), Plan, Implement, and Evaluate. The Agency for Healthcare Research and Quality (AHRQ) emphasizes structured clinical workflows as a foundation of patient safety.<sup>1</sup> ADPIE turns observations into accountable care plans.',
                    'Assessment gathers subjective and objective data—interview, inspection, palpation, and review of the medical record. Avoid jumping to interventions before understanding the problem.',
                ],
            ],
            [
                'heading' => 'Planning with population health in mind',
                'paragraphs' => [
                    'HHS Healthy People 2030 sets national objectives for preventive care and chronic disease management.<sup>2</sup> LPN care plans connect individual patients to those goals: immunizations, fall prevention, glycemic control, and health literacy.',
                    'Outcomes should be measurable—“patient will ambulate 50 feet with standby assist by day two”—not vague wishes.',
                ],
            ],
            [
                'heading' => 'Evaluation closes the loop',
                'paragraphs' => [
                    'If outcomes are not met, revisit assessment rather than repeating failed interventions. Document revision transparently.',
                    'NCLEX-PN items often test whether you recognize which phase of the process a scenario describes—listen for data collection vs. implementation cues.',
                ],
            ],
        ],
    ],
    [
        'slug' => 'infection-prevention',
        'title' => 'Infection Prevention & CDC Transmission Precautions',
        'excerpt' => 'Standard precautions, isolation categories, and hand hygiene from authoritative CDC guidance.',
        'category' => 'Safety',
        'accent' => 'ems',
        'symbols' => '🧤',
        'keywords' => ['infection', 'precautions', 'hand hygiene', 'ppe', 'isolation'],
        'sources' => [
            ['id' => 1, 'label' => 'CDC — Infection Control in Healthcare Settings', 'url' => 'https://www.cdc.gov/healthcare-associated-infections/'],
            ['id' => 2, 'label' => 'CDC — Hand Hygiene in Healthcare Settings', 'url' => 'https://www.cdc.gov/handhygiene/'],
        ],
        'sections' => [
            [
                'heading' => 'Standard precautions for every patient',
                'paragraphs' => [
                    'The CDC applies standard precautions to all patient contact—hand hygiene, safe injection practices, respiratory hygiene, and appropriate PPE when exposure to blood or body fluids is possible.<sup>1</sup> Treat every patient as potentially infectious.',
                    'Hand hygiene is the single most effective infection prevention act. The CDC recommends alcohol-based rub when hands are not visibly soiled, and soap and water when they are or after caring for C. difficile patients.<sup>2</sup>',
                ],
            ],
            [
                'heading' => 'Transmission-based precautions',
                'paragraphs' => [
                    'Contact precautions protect against MRSA and wound infections; droplet for influenza and pertussis; airborne for tuberculosis and measles (fit-tested N95 or higher). Know your facility’s signage and PPE station layout.',
                    'Practical nurses often perform direct care in isolation rooms—don and doff PPE in correct order to avoid self-contamination.',
                ],
            ],
            [
                'heading' => 'Surveillance and reporting',
                'paragraphs' => [
                    'Healthcare-associated infections are tracked nationally to drive improvement.<sup>1</sup> Report breaches, exposures, and positive cultures per facility policy.',
                    'Vaccination of healthcare workers, including annual influenza immunization, protects patients and staff—a professional obligation.',
                ],
            ],
        ],
    ],
    [
        'slug' => 'medication-rights',
        'title' => 'Medication Safety & the Nine Rights',
        'excerpt' => 'FDA-regulated drug safety principles and the rights framework LPNs use before every administration.',
        'category' => 'Pharmacology',
        'accent' => 'pharma',
        'symbols' => '💊',
        'keywords' => ['medication', 'rights', 'fda', 'dosage', 'mar'],
        'sources' => [
            ['id' => 1, 'label' => 'FDA — Medication Errors Related to CDER-Regulated Products', 'url' => 'https://www.fda.gov/drugs/medication-errors-related-cder-regulated-drug-products'],
            ['id' => 2, 'label' => 'NIH MedlinePlus — Medication safety', 'url' => 'https://medlineplus.gov/medicationsafety.html'],
        ],
        'sections' => [
            [
                'heading' => 'Rights before every dose',
                'paragraphs' => [
                    'Verify right patient, drug, dose, route, time, documentation, reason, response, and refusal when applicable. The FDA tracks medication errors involving approved drugs and issues safety communications to prevent harm.<sup>1</sup>',
                    'MedlinePlus reminds consumers and clinicians alike to read labels, understand side effects, and store medications properly—habits that start with nursing verification.<sup>2</sup>',
                ],
            ],
            [
                'heading' => 'High-risk situations',
                'paragraphs' => [
                    'Look-alike/sound-alike drugs, pediatric liquid concentrations, and insulin require independent double checks when policy mandates. Never crush sustained-release formulations unless pharmacy approves.',
                    'The Medication Administration Record (MAR) is a legal document—chart immediately after administration, not before.',
                ],
            ],
            [
                'heading' => 'Scope and delegation',
                'paragraphs' => [
                    'LPNs administer medications per state nurse practice act and facility policy. Intravenous therapy privileges vary by jurisdiction—know your limits and escalate when orders exceed scope.',
                    'Question unclear orders through proper channels; patient safety outweighs convenience.',
                ],
            ],
        ],
    ],
    [
        'slug' => 'vital-signs-assessment',
        'title' => 'Vital Signs & Baseline Physiologic Assessment',
        'excerpt' => 'Measuring and interpreting temperature, pulse, respiration, and blood pressure using NIH clinical references.',
        'category' => 'Assessment',
        'accent' => 'rescue',
        'symbols' => '🩺',
        'keywords' => ['vitals', 'blood pressure', 'pulse', 'temperature', 'respirations'],
        'sources' => [
            ['id' => 1, 'label' => 'NIH MedlinePlus — Vital signs', 'url' => 'https://medlineplus.gov/ency/article/002341.htm'],
            ['id' => 2, 'label' => 'CDC — High Blood Pressure', 'url' => 'https://www.cdc.gov/high-blood-pressure/'],
        ],
        'sections' => [
            [
                'heading' => 'What vitals reveal',
                'paragraphs' => [
                    'MedlinePlus defines vital signs as temperature, pulse, respiration rate, and blood pressure—the core indicators of how the body is functioning.<sup>1</sup> Trends matter more than isolated numbers.',
                    'Pain is often considered the “fifth vital sign,” though assessment must distinguish acute injury from chronic conditions and cultural expression of discomfort.',
                ],
            ],
            [
                'heading' => 'Technique and error reduction',
                'paragraphs' => [
                    'Use appropriate cuff size—too small falsely elevates blood pressure. Position the patient with back supported, feet flat, and arm at heart level when possible.',
                    'The CDC notes nearly half of U.S. adults have hypertension, many undiagnosed.<sup>2</sup> Report critical values immediately per protocol.',
                ],
            ],
            [
                'heading' => 'When to reassess',
                'paragraphs' => [
                    'Re-measure after interventions: antipyretics, fluid boluses, or bronchodilators. Document time, route, and patient activity level—they all influence readings.',
                    'Orthostatic vital signs detect volume depletion—compare lying, sitting, and standing measurements when dizziness or bleeding is suspected.',
                ],
            ],
        ],
    ],
    [
        'slug' => 'patient-safety-communication',
        'title' => 'Patient Safety & Therapeutic Communication',
        'excerpt' => 'AHRQ safety science and HHS guidance on respectful, effective nurse-patient communication.',
        'category' => 'Communication',
        'accent' => 'safety',
        'symbols' => '💬',
        'keywords' => ['safety', 'communication', 'falls', 'advocacy', 'therapeutic'],
        'sources' => [
            ['id' => 1, 'label' => 'AHRQ — Patient Safety Network', 'url' => 'https://psnet.ahrq.gov/'],
            ['id' => 2, 'label' => 'HHS — Office for Civil Rights — HIPAA', 'url' => 'https://www.hhs.gov/hipaa/index.html'],
        ],
        'sections' => [
            [
                'heading' => 'Safety as a system property',
                'paragraphs' => [
                    'AHRQ’s Patient Safety Network disseminates research on errors, near misses, and prevention strategies.<sup>1</sup> Individual mistakes often reflect system gaps—unclear orders, poor handoffs, or inadequate staffing. Speak up when conditions threaten safety.',
                    'Practical nurses are at the bedside longest; you may be the first to notice delirium, aspiration risk, or suicidal ideation.',
                ],
            ],
            [
                'heading' => 'Therapeutic communication',
                'paragraphs' => [
                    'Active listening, open-ended questions, and reflecting feelings build trust. Avoid false reassurance (“everything will be fine”) when uncertainty exists—instead, explain what you know and what you will do next.',
                    'Cultural humility improves care: ask preferred language, involve interpreters for medical content, and respect modesty and family roles.',
                ],
            ],
            [
                'heading' => 'Privacy and advocacy',
                'paragraphs' => [
                    'HHS enforces HIPAA protections for health information.<sup>2</sup> Discuss care in private, shield screens during procedures, and share only what team members need to know.',
                    'Advocacy means protecting patients from harm—even when it requires escalating concerns to RNs, providers, or rapid response teams.',
                ],
            ],
        ],
    ],
];
