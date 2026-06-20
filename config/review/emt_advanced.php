<?php

return [
    [
        'slug' => 'advanced-airway-adjuncts',
        'title' => 'Advanced Airway Adjuncts & Supraglottic Devices',
        'excerpt' => 'Airway escalation at the AEMT level, with context from NIH respiratory resources and NHTSA EMS education priorities.',
        'category' => 'Airway',
        'accent' => 'ems',
        'symbols' => '🫁 💨 🩺',
        'keywords' => ['airway', 'supraglottic', 'adjunct', 'intubation', 'aemt'],
        'sources' => [
            ['id' => 1, 'label' => 'NIH MedlinePlus — Breathing difficulty', 'url' => 'https://medlineplus.gov/breathingproblems.html'],
            ['id' => 2, 'label' => 'NHTSA — Emergency Medical Services', 'url' => 'https://www.nhtsa.gov/emergency-medical-services'],
        ],
        'sections' => [
            [
                'heading' => 'When basic maneuvers are not enough',
                'paragraphs' => [
                    'Advanced EMTs bridge basic and paramedic care. When positioning and suction fail to maintain a patent airway, adjuncts—oropharyngeal and nasopharyngeal airways—restore patency in unconscious patients without gag reflex.<sup>1</sup> Device selection depends on patient size, consciousness, and trauma considerations.',
                    'NHTSA’s national EMS framework emphasizes progressive skill acquisition tied to demonstrated competency—not procedure volume alone.<sup>2</sup> Each airway attempt should be deliberate, with continuous oxygenation and ventilation assessment.',
                ],
            ],
            [
                'heading' => 'Supraglottic airways in AEMT scope',
                'paragraphs' => [
                    'Many AEMT programs include supraglottic airway (SGA) placement where state law permits. SGAs sit above the vocal cords, providing a conduit for positive-pressure ventilation without tracheal intubation. Proper sizing, lubrication, and confirmation techniques (ventilation compliance, capnography when available) are essential.',
                    'Failed placement should trigger a stepwise plan: re-oxygenate, reconsider BVM with two-person technique, and request paramedic intercept when local protocols allow.',
                ],
            ],
            [
                'heading' => 'Human factors and reassessment',
                'paragraphs' => [
                    'Airway management is as much about teamwork as technique. Assign a compressor or ventilator, monitor saturation continuously, and limit apneic periods during attempts.',
                    'Reassess after every intervention and after every patient move—tube dislodgement and vomiting are common en route complications.',
                ],
            ],
        ],
    ],
    [
        'slug' => 'iv-therapy-infection',
        'title' => 'IV Access & Infection Prevention in the Field',
        'excerpt' => 'Vascular access fundamentals and CDC-aligned infection control for Advanced EMT practice.',
        'category' => 'Procedures',
        'accent' => 'medic',
        'symbols' => '💉 💧 ✚',
        'keywords' => ['iv', 'infection', 'aseptic', 'catheter', 'fluids'],
        'sources' => [
            ['id' => 1, 'label' => 'CDC — Injection Safety', 'url' => 'https://www.cdc.gov/injection-safety/'],
            ['id' => 2, 'label' => 'NIH MedlinePlus — Intravenous fluids', 'url' => 'https://medlineplus.gov/ency/article/002383.htm'],
        ],
        'sections' => [
            [
                'heading' => 'Why IV access matters at AEMT level',
                'paragraphs' => [
                    'Intravenous access enables fluid resuscitation and medication delivery beyond EMT-Basic scope. MedlinePlus notes that IV fluids can replace volume lost from bleeding, vomiting, or dehydration—when clinical judgment supports their use.<sup>2</sup>',
                    'IV therapy is not automatic for every patient. Weigh benefits against on-scene time, patient stability, and transport distance.',
                ],
            ],
            [
                'heading' => 'Aseptic technique',
                'paragraphs' => [
                    'The CDC promotes safe injection practices: use single-dose vials appropriately, never reuse needles or syringes, and maintain sterile technique during catheter insertion.<sup>1</sup> Field conditions challenge sterility—minimize contamination by preparing supplies before skin prep and avoiding open packaging in wind or dirt.',
                    'Document site, catheter gauge, attempts, and complications. Local infection or infiltration requires removal and reassessment of alternate sites.',
                ],
            ],
            [
                'heading' => 'Fluids and monitoring',
                'paragraphs' => [
                    'Isotonic crystalloids are common first-line volume expanders for hemorrhagic and distributive shock within protocol. Monitor for fluid overload signs—crackles, increasing work of breathing, and hypertension—in susceptible patients.',
                    'Pair fluid therapy with hemorrhage control; IV fluids do not replace definitive surgical care for internal bleeding.',
                ],
            ],
        ],
    ],
    [
        'slug' => 'medication-administration',
        'title' => 'Medication Administration & FDA Drug Safety',
        'excerpt' => 'AEMT medication responsibilities framed by FDA labeling principles and safe administration practices.',
        'category' => 'Pharmacology',
        'accent' => 'pharma',
        'symbols' => '💊 💉 ✓',
        'keywords' => ['medication', 'fda', 'dosage', 'routes', 'allergy'],
        'sources' => [
            ['id' => 1, 'label' => 'FDA — Drug Safety and Availability', 'url' => 'https://www.fda.gov/drugs/drug-safety-and-availability'],
            ['id' => 2, 'label' => 'NIH DailyMed — Drug labeling information', 'url' => 'https://dailymed.nlm.nih.gov/dailymed/'],
        ],
        'sections' => [
            [
                'heading' => 'Rights of medication administration',
                'paragraphs' => [
                    'Before every medication, verify right patient, drug, dose, route, and time—plus allergies and contraindications. The FDA oversees drug approval, labeling, and post-market safety surveillance; providers must follow approved indications and local protocol.<sup>1</sup>',
                    'NIH’s DailyMed repository publishes official prescribing information—useful for confirming concentrations, routes, and warnings when protocol allows.<sup>2</sup>',
                ],
            ],
            [
                'heading' => 'Common AEMT medications',
                'paragraphs' => [
                    'Depending on state scope, AEMTs may administer analgesics, antiemetics, bronchodilators, glucagon, epinephrine for anaphylaxis, and other protocol-listed agents. Calculate pediatric doses carefully; use length-based tapes when authorized.',
                    'Document the drug, dose, time, response, and adverse effects. Report medication errors through agency quality-improvement channels—they drive system learning, not blame alone.',
                ],
            ],
            [
                'heading' => 'Controlled substances and accountability',
                'paragraphs' => [
                    'Some AEMT services carry controlled substances under strict chain-of-custody rules. Waste, witness requirements, and inventory checks are legal obligations—not paperwork exercises.',
                    'When in doubt about compatibility or dose, contact medical direction per protocol rather than guessing.',
                ],
            ],
        ],
    ],
    [
        'slug' => 'cardiac-monitoring-basics',
        'title' => 'Cardiac Monitoring & Rhythm Recognition',
        'excerpt' => 'Introduction to 3-lead monitoring and dysrhythmia awareness using NIH cardiovascular education resources.',
        'category' => 'Cardiac',
        'accent' => 'rescue',
        'symbols' => '📈 ❤️ ⚡',
        'keywords' => ['ecg', 'monitor', 'rhythm', 'cardiac', 'palpitations'],
        'sources' => [
            ['id' => 1, 'label' => 'NIH NHLBI — How the Heart Works', 'url' => 'https://www.nhlbi.nih.gov/health/heart'],
            ['id' => 2, 'label' => 'CDC — Heart Disease Facts', 'url' => 'https://www.cdc.gov/heart-disease/about/index.html'],
        ],
        'sections' => [
            [
                'heading' => 'Electrical activity and perfusion',
                'paragraphs' => [
                    'The heart’s electrical system coordinates contraction and forward flow.<sup>1</sup> AEMTs attach monitor leads to detect rate, rhythm, and signs of ischemia—not to replace 12-lead acquisition performed by paramedics when indicated.',
                    'Heart disease remains a leading U.S. health concern;<sup>2</sup> many EMS calls involve chest discomfort, syncope, or palpitations where monitoring guides urgency and destination.',
                ],
            ],
            [
                'heading' => 'Lead placement and artifact',
                'paragraphs' => [
                    'Correct lead placement improves trace quality. Dry skin, secure electrodes, and patient stillness reduce motion artifact. Compare the monitor to the patient: a slow rhythm with a strong pulse may differ from pulseless electrical activity.',
                    'Treat unstable patients per protocol—synchronized cardioversion and advanced interventions may require paramedic backup.',
                ],
            ],
            [
                'heading' => 'Documentation and trend monitoring',
                'paragraphs' => [
                    'Capture rhythm strips for symptomatic events, before and after interventions, and during transport changes. Note pain scores, blood pressure, and exam findings alongside the rhythm.',
                    'Trending helps receiving teams see evolution—not a single snapshot in time.',
                ],
            ],
        ],
    ],
    [
        'slug' => 'trauma-triage-start',
        'title' => 'Trauma Triage & the START System',
        'excerpt' => 'Mass-casualty sorting principles from CDC field triage guidance and standardized START methodology.',
        'category' => 'Trauma',
        'accent' => 'safety',
        'symbols' => '🏷️ 🔴 🟡 🟢',
        'keywords' => ['triage', 'start', 'mci', 'trauma', 'sorting'],
        'sources' => [
            ['id' => 1, 'label' => 'CDC — Guidelines for Field Triage of Injured Patients', 'url' => 'https://www.cdc.gov/fieldtriage/'],
            ['id' => 2, 'label' => 'FEMA — National Incident Management System', 'url' => 'https://www.fema.gov/emergency-managers/nims'],
        ],
        'sections' => [
            [
                'heading' => 'Individual vs. mass-casualty triage',
                'paragraphs' => [
                    'Routine EMS triage identifies the sickest patient in a small scene. Mass-casualty incidents (MCIs) invert priorities: do the greatest good for the greatest number when resources are overwhelmed. The CDC’s field triage guidelines inform both trauma center destination decisions and physiologic urgency.<sup>1</sup>',
                    'FEMA’s National Incident Management System (NIMS) provides the command structure—incident command, staging, and unified communications—that makes triage tags meaningful.<sup>2</sup>',
                ],
            ],
            [
                'heading' => 'START in practice',
                'paragraphs' => [
                    'Simple Triage and Rapid Treatment (START) uses respiration, perfusion (radial pulse or capillary refill), and mental status (AVPU) to assign immediate, delayed, minor, or expectant categories. Walkers with minor injuries may be directed to a collection point, freeing responders for critical patients.',
                    'AEMTs often staff triage sectors during MCIs. Clear tagging, one-way patient flow, and frequent re-triage prevent category drift as patients deteriorate or improve.',
                ],
            ],
            [
                'heading' => 'Integration with transport',
                'paragraphs' => [
                    'Triage without transport is sorting without care. Coordinate ambulance staging, helicopter requests, and hospital notifications through the operations section.',
                    'Document triage category, time, and reassessments. After-action reviews improve the next response.',
                ],
            ],
        ],
    ],
];
