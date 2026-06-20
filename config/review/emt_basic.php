<?php

return [
    [
        'slug' => 'primary-assessment',
        'title' => 'The Primary Assessment: A Systematic ABCDE Approach',
        'excerpt' => 'How federal EMS guidance frames scene safety, rapid assessment, and life-threat identification for every patient encounter.',
        'category' => 'Assessment',
        'accent' => 'ems',
        'symbols' => '🩺',
        'keywords' => ['abcde', 'primary', 'assessment', 'scene safety', 'life threats'],
        'sources' => [
            ['id' => 1, 'label' => 'NHTSA — Emergency Medical Services', 'url' => 'https://www.nhtsa.gov/emergency-medical-services'],
            ['id' => 2, 'label' => 'CDC — Guidelines for Field Triage of Injured Patients', 'url' => 'https://www.cdc.gov/fieldtriage/'],
        ],
        'sections' => [
            [
                'heading' => 'Why a fixed sequence matters',
                'paragraphs' => [
                    'The National Highway Traffic Safety Administration (NHTSA) coordinates national efforts to strengthen emergency medical services, including education that emphasizes consistent patient assessment across jurisdictions.<sup>1</sup> For EMT-Basic providers, that consistency begins with treating assessment as a repeatable process—not improvisation under stress.',
                    'A primary survey follows the ABCDE order: Airway, Breathing, Circulation, Disability (neurologic status), and Exposure/environmental control. Each step addresses conditions that can kill within minutes if missed. Moving forward only after stabilizing—or rapidly correcting—the current letter prevents hidden threats from compounding.',
                ],
            ],
            [
                'heading' => 'Scene context and triage thinking',
                'paragraphs' => [
                    'Before hands touch a patient, providers evaluate scene safety, mechanism of injury, and resource needs. The Centers for Disease Control and Prevention (CDC) publishes field triage guidelines that help EMS identify patients who need trauma center care based on physiology, anatomy, mechanism, and special considerations.<sup>2</sup> Even when you are not running a mass-casualty incident, that same disciplined thinking—identify the sickest patient first—anchors the primary assessment.',
                    'Document findings as you go. A clear baseline mental status, respiratory rate, skin signs, and chief complaint narrative supports handoff to advanced providers and reduces information loss during transport.',
                ],
            ],
            [
                'heading' => 'From assessment to action',
                'paragraphs' => [
                    'The primary assessment is not a checklist to complete in silence—it drives immediate interventions. Compromised airway demands positioning, suction, or basic adjuncts; inadequate breathing may require oxygen or ventilatory assistance within your scope; poor perfusion triggers hemorrhage control and shock management.',
                    'Reassessment closes the loop. Patients change en route; a stable airway can obstruct, and compensated shock can decompensate. Brief, repeated primary surveys are a hallmark of safe basic-level care.',
                ],
            ],
        ],
    ],
    [
        'slug' => 'airway-breathing-basics',
        'title' => 'Airway & Breathing: Opening the Path for Oxygen',
        'excerpt' => 'Foundational airway positioning, breathing evaluation, and oxygen principles aligned with NIH and CDC respiratory guidance.',
        'category' => 'Airway',
        'accent' => 'medic',
        'symbols' => '🫁',
        'keywords' => ['airway', 'breathing', 'oxygen', 'respiratory', 'jaw thrust'],
        'sources' => [
            ['id' => 1, 'label' => 'NIH MedlinePlus — Breathing difficulty', 'url' => 'https://medlineplus.gov/breathingproblems.html'],
            ['id' => 2, 'label' => 'CDC — Chronic Obstructive Pulmonary Disease (COPD)', 'url' => 'https://www.cdc.gov/copd/'],
        ],
        'sections' => [
            [
                'heading' => 'Airway patency comes first',
                'paragraphs' => [
                    'Breathing problems are among the most common reasons people seek emergency care.<sup>1</sup> At the EMT-Basic level, “airway” means ensuring the upper airway is open so air can reach the lungs. Manual maneuvers—head-tilt chin-lift when spinal injury is not suspected, jaw-thrust when it is—remain the first tools before adjuncts or advanced techniques.',
                    'Listen and look: stridor, gurgling, or visible obstructions demand immediate action. Basic suction can clear fluids; solid obstructions may require finger sweeps only when you can see the object. Protecting the cervical spine while opening the airway is a core basic skill.',
                ],
            ],
            [
                'heading' => 'Evaluating breathing quality',
                'paragraphs' => [
                    'Once the airway is open, assess whether breathing is adequate: rate, rhythm, depth, and effort. Nasal flaring, retractions, tripod positioning, and speaking in single words signal increased work of breathing. Pulse oximetry, when available, supplements—but never replaces—clinical observation.',
                    'Many patients with chronic lung disease live with lower baseline saturations.<sup>2</sup> Treat the patient: compare current findings to reported normals, watch for acute change, and support oxygenation per local protocol when signs of hypoxia or shock are present.',
                ],
            ],
            [
                'heading' => 'Oxygen and ventilation support',
                'paragraphs' => [
                    'EMT-Basic scope typically includes oxygen delivery by nasal cannula, non-rebreather mask, and bag-valve-mask ventilation for apneic or severely inadequate breathing. Match the device to the patient’s need and reassess after every adjustment.',
                    'Document liters per minute, device type, and response. Effective basic airway and breathing care buys time—the foundation on which all other interventions rest.',
                ],
            ],
        ],
    ],
    [
        'slug' => 'cardiac-arrest-cpr',
        'title' => 'Sudden Cardiac Arrest & High-Quality CPR',
        'excerpt' => 'What federal health agencies emphasize about cardiac arrest recognition, immediate CPR, and early defibrillation.',
        'category' => 'Cardiac',
        'accent' => 'rescue',
        'symbols' => '🫀',
        'keywords' => ['cpr', 'cardiac arrest', 'aed', 'defibrillation', 'compressions'],
        'sources' => [
            ['id' => 1, 'label' => 'CDC — About Cardiac Arrest', 'url' => 'https://www.cdc.gov/heart-disease/about/cardiac-arrest.htm'],
            ['id' => 2, 'label' => 'NIH NHLBI — CPR', 'url' => 'https://www.nhlbi.nih.gov/health/cpr'],
        ],
        'sections' => [
            [
                'heading' => 'Recognizing cardiac arrest',
                'paragraphs' => [
                    'Cardiac arrest occurs when the heart suddenly stops pumping effectively. The CDC notes it is a leading cause of death and can happen without warning.<sup>1</sup> EMS providers confirm unresponsiveness and absent or abnormal breathing, then immediately begin chest compressions unless contraindicated.',
                    'Agonal gasps are not effective breathing—treat them as arrest. Minimize pauses; perfusion drops within seconds when compressions stop.',
                ],
            ],
            [
                'heading' => 'Compression fundamentals',
                'paragraphs' => [
                    'The National Heart, Lung, and Blood Institute describes CPR as chest compressions combined with rescue breaths (or compression-only CPR for untrained bystanders).<sup>2</sup> For professionals, high-quality CPR means adequate depth, full recoil, correct rate (100–120/min), and minimal interruption.',
                    'Rotate compressors every two minutes to prevent fatigue. Capnography, when available at your level, helps confirm airway placement and perfusion during advanced care; at basic level, focus on mechanical excellence and early AED application.',
                ],
            ],
            [
                'heading' => 'AEDs and team coordination',
                'paragraphs' => [
                    'Defibrillation is most effective when delivered early for shockable rhythms. EMT-Basics deploy the AED, clear the patient, and resume compressions immediately after shock or rhythm analysis per device prompts.',
                    'Assign roles: compressor, airway, recorder, and scene manager. Clear communication and closed-loop orders (“compressions started,” “shock delivered, resume CPR”) improve outcomes in the chaotic first minutes of arrest care.',
                ],
            ],
        ],
    ],
    [
        'slug' => 'bleeding-shock',
        'title' => 'Bleeding Control & Shock Recognition',
        'excerpt' => 'Hemorrhage management and perfusion assessment grounded in CDC trauma triage and NIH clinical references.',
        'category' => 'Trauma',
        'accent' => 'rescue',
        'symbols' => '🩹',
        'keywords' => ['bleeding', 'shock', 'hemorrhage', 'tourniquet', 'perfusion'],
        'sources' => [
            ['id' => 1, 'label' => 'CDC — Guidelines for Field Triage of Injured Patients', 'url' => 'https://www.cdc.gov/fieldtriage/'],
            ['id' => 2, 'label' => 'NIH MedlinePlus — Shock', 'url' => 'https://medlineplus.gov/ency/article/000167.htm'],
        ],
        'sections' => [
            [
                'heading' => 'Stopping life-threatening hemorrhage',
                'paragraphs' => [
                    'Uncontrolled bleeding is a leading preventable cause of trauma death. Direct pressure remains the first intervention for most external hemorrhage. When extremity bleeding cannot be controlled, tourniquets—applied high and tight—are appropriate within basic scope and training.',
                    'The CDC’s field triage guidance highlights abnormal physiology—including hypotension and poor perfusion—as indicators of severe injury requiring rapid transport to definitive care.<sup>1</sup> Controlling bleeding on scene directly affects whether the patient arrives alive.',
                ],
            ],
            [
                'heading' => 'Understanding shock',
                'paragraphs' => [
                    'Shock is inadequate tissue perfusion. MedlinePlus, a service of the National Library of Medicine, describes how the body compensates early—with tachycardia, anxiety, and cool, pale skin—before blood pressure falls.<sup>2</sup> EMTs must recognize compensated shock before decompensation.',
                    'Treat causes you can address: hemorrhage, airway failure, tension pneumothorax (recognize and support per scope), and severe allergic reactions. Positioning, warmth, and oxygen support perfusion while minimizing on-scene time.',
                ],
            ],
            [
                'heading' => 'Circulation in the primary survey',
                'paragraphs' => [
                    'Assess pulses, skin color, temperature, and mental status together. In trauma, expose quietly to find hidden bleeding while preventing hypothermia—a contributor to the lethal triad of acidosis, coagulopathy, and hypothermia in severe injury.',
                    'Repeat perfusion checks after every intervention. A patient who “looks better” after tourniquet placement still needs urgent transport and surgical capability.',
                ],
            ],
        ],
    ],
    [
        'slug' => 'scope-ethics-privacy',
        'title' => 'Scope of Practice, Ethics & Patient Privacy',
        'excerpt' => 'How NHTSA frames EMS systems and how HHS HIPAA rules protect patient information in the field.',
        'category' => 'Professional',
        'accent' => 'pharma',
        'symbols' => '🔒',
        'keywords' => ['scope', 'ethics', 'hipaa', 'privacy', 'consent'],
        'sources' => [
            ['id' => 1, 'label' => 'NHTSA — Emergency Medical Services', 'url' => 'https://www.nhtsa.gov/emergency-medical-services'],
            ['id' => 2, 'label' => 'HHS — HIPAA for Professionals', 'url' => 'https://www.hhs.gov/hipaa/for-professionals/index.html'],
        ],
        'sections' => [
            [
                'heading' => 'Working within your scope',
                'paragraphs' => [
                    'NHTSA supports national EMS education and system standards so providers deliver care aligned with their certification level.<sup>1</sup> EMT-Basic scope includes foundational assessment, airway and breathing support, hemorrhage control, splinting, and transport—never skills reserved for advanced or paramedic licensure unless local protocol explicitly extends them.',
                    'When care exceeds your scope, the ethical response is rapid notification, basic life support, and timely handoff—not improvisation beyond training.',
                ],
            ],
            [
                'heading' => 'Consent, refusal, and advocacy',
                'paragraphs' => [
                    'Competent adults may refuse transport after informed discussion of risks. Document capacity assessment, information provided, and patient statements. For minors and incapacitated patients, implied consent often applies in emergencies.',
                    'Advocacy means communicating patient needs clearly to receiving facilities and protecting dignity—covering patients, limiting unnecessary exposure, and involving family when appropriate.',
                ],
            ],
            [
                'heading' => 'HIPAA in EMS',
                'paragraphs' => [
                    'The U.S. Department of Health and Human Services administers HIPAA privacy rules governing protected health information.<sup>2</sup> Prehospital charts, radio reports, and handoffs are covered. Share information only with those involved in care, use secure documentation practices, and avoid discussing patients in public areas.',
                    'Professional ethics and legal compliance reinforce trust—the same trust that lets you enter homes and care for people at their most vulnerable moments.',
                ],
            ],
        ],
    ],
];
