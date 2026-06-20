<?php

require_once __DIR__.'/helpers.php';

return nclex_levels([
    [
        'title' => 'Post-operative Respiratory Depression',
        'scenario' => 'A 68-year-old client is 2 hours post–total knee replacement. SpO₂ is 88% on room air, respirations are 8/min and shallow, and the client is difficult to arouse after receiving IV morphine.',
        'question' => 'Which action should the PN take first?',
        'options' => [
            'notify' => 'Notify the surgeon and request naloxone',
            'airway' => 'Position the airway, stimulate the client, and apply supplemental oxygen',
            'vitals' => 'Obtain a full set of vital signs and document findings',
            'narcan' => 'Administer naloxone per standing order immediately',
        ],
        'correct' => 'airway',
        'explanation' => 'Airway and breathing take priority over circulation and medication reversal. Open the airway, stimulate the client, and provide oxygen while preparing to notify the provider and administer naloxone if ordered.',
        'level_options' => [
            4 => [
                'notify' => 'Call the rapid response team and prepare for intubation',
                'airway' => 'Position the airway, stimulate the client, and apply supplemental oxygen',
                'vitals' => 'Complete a focused respiratory assessment and pulse oximetry',
                'narcan' => 'Administer naloxone IV push now',
            ],
            5 => [
                'notify' => 'Increase IV fluid rate and recheck blood pressure',
                'airway' => 'Position the airway, stimulate the client, and apply supplemental oxygen',
                'vitals' => 'Auscultate lung sounds and document sedation score',
                'narcan' => 'Administer naloxone and prepare for discharge teaching',
            ],
        ],
    ],
    [
        'title' => 'Acute Pulmonary Edema',
        'scenario' => 'A client with heart failure returns from the bathroom pale, diaphoretic, and gasping. You hear coarse crackles bilaterally, SpO₂ is 84% on 2 L nasal cannula, and the client says, "I cannot catch my breath."',
        'question' => 'What is the priority nursing action?',
        'options' => [
            'diuretic' => 'Administer the scheduled furosemide early',
            'high_flow' => 'Sit the client upright and increase oxygen delivery',
            'weight' => 'Weigh the client and strict intake/output',
            'morphine' => 'Administer morphine sulfate for anxiety',
        ],
        'correct' => 'high_flow',
        'explanation' => 'Breathing is the immediate priority. Position the client upright (high Fowler) to reduce preload and increase oxygen delivery while preparing to notify the provider for diuretics and other CHF interventions.',
        'level_options' => [
            4 => [
                'diuretic' => 'Give IV furosemide and restrict fluids',
                'high_flow' => 'Sit the client upright and increase oxygen delivery',
                'weight' => 'Obtain daily weight and assess peripheral edema',
                'morphine' => 'Provide emotional support and relaxation techniques',
            ],
            5 => [
                'diuretic' => 'Start a nitroglycerin infusion for afterload reduction',
                'high_flow' => 'Sit the client upright and increase oxygen delivery',
                'weight' => 'Review discharge instructions on sodium restriction',
                'morphine' => 'Encourage the client to use the incentive spirometer',
            ],
        ],
    ],
    [
        'title' => 'Anaphylaxis After Antibiotic',
        'scenario' => 'Minutes after receiving IV ceftriaxone, a client develops audible wheezing, throat tightness, generalized urticaria, and blood pressure 86/52 mm Hg.',
        'question' => 'Which intervention should the PN perform first?',
        'options' => [
            'epi' => 'Administer epinephrine IM per protocol',
            'airway' => 'Assess airway patency and apply high-flow oxygen',
            'stop' => 'Stop the antibiotic infusion and flush the line',
            'histamine' => 'Give diphenhydramine IV for itching',
        ],
        'correct' => 'airway',
        'explanation' => 'Airway compromise from bronchospasm and laryngeal edema is life-threatening. Assess and support airway and breathing while stopping the infusion, calling for help, and preparing epinephrine — but airway/breathing interventions are first.',
        'level_options' => [
            4 => [
                'epi' => 'Draw up epinephrine and administer IM immediately',
                'airway' => 'Assess airway patency and apply high-flow oxygen',
                'stop' => 'Stop the antibiotic and start a normal saline infusion',
                'histamine' => 'Apply cool compresses for urticaria',
            ],
            5 => [
                'epi' => 'Administer epinephrine and prepare for discharge',
                'airway' => 'Assess airway patency and apply high-flow oxygen',
                'stop' => 'Document the allergy and continue monitoring vitals only',
                'histamine' => 'Offer oral antihistamine and reschedule the antibiotic',
            ],
        ],
    ],
    [
        'title' => 'Partial Airway Obstruction',
        'scenario' => 'During lunch, a 72-year-old client clutches the throat, cannot speak, but coughs weakly with stridor and SpO₂ 91%.',
        'question' => 'What should the PN do first?',
        'options' => [
            'heimlich' => 'Perform abdominal thrusts immediately',
            'encourage' => 'Encourage forceful coughing and stay with the client',
            'back' => 'Deliver five back blows followed by five abdominal thrusts',
            'suction' => 'Obtain suction equipment and inspect the mouth',
        ],
        'correct' => 'encourage',
        'explanation' => 'A weak cough with some air movement suggests partial obstruction. Encourage coughing, stay with the client, and prepare to escalate to back blows and abdominal thrusts if the client becomes unable to cough, speak, or breathe.',
        'level_options' => [
            4 => [
                'heimlich' => 'Start cycles of abdominal thrusts without delay',
                'encourage' => 'Encourage forceful coughing and stay with the client',
                'back' => 'Position for back blows and monitor oxygen saturation',
                'suction' => 'Perform oropharyngeal suctioning under direct vision',
            ],
            5 => [
                'heimlich' => 'Begin CPR compressions at 100–120/min',
                'encourage' => 'Encourage forceful coughing and stay with the client',
                'back' => 'Give sips of water to dislodge the obstruction',
                'suction' => 'Place the client supine and open the airway with head tilt',
            ],
        ],
    ],
    [
        'title' => 'Postpartum Hemorrhage',
        'scenario' => 'Four hours after vaginal delivery, a client soaks two perineal pads in 15 minutes, heart rate 118/min, blood pressure 92/58 mm Hg, and the uterus is boggy above the umbilicus.',
        'question' => 'Which action takes priority?',
        'options' => [
            'fundal' => 'Massage the fundus and ensure the bladder is empty',
            'iv' => 'Start a second large-bore IV and type and crossmatch',
            'pitocin' => 'Administer oxytocin per protocol',
            'pad' => 'Weigh perineal pads to quantify blood loss',
        ],
        'correct' => 'fundal',
        'explanation' => 'Active hemorrhage threatens circulation. First-line management for uterine atony is fundal massage and bladder emptying to promote uterine contraction, while simultaneously calling for help and preparing blood products and uterotonics.',
        'level_options' => [
            4 => [
                'fundal' => 'Massage the fundus and ensure the bladder is empty',
                'iv' => 'Establish IV access and begin fluid resuscitation',
                'pitocin' => 'Administer oxytocin IV bolus per protocol',
                'pad' => 'Continue pad counts and notify the provider',
            ],
            5 => [
                'fundal' => 'Massage the fundus and ensure the bladder is empty',
                'iv' => 'Teach breastfeeding techniques to promote oxytocin release',
                'pitocin' => 'Prepare newborn bonding and skin-to-skin care',
                'pad' => 'Document lochia color and odor on the flow sheet',
            ],
        ],
    ],
    [
        'title' => 'Severe Asthma Exacerbation',
        'scenario' => 'A 19-year-old with asthma speaks in short word clusters, has suprasternal retractions, peak flow 40% of personal best, and SpO₂ 89% on room air.',
        'question' => 'What is the PN\'s first action?',
        'options' => [
            'neb' => 'Administer a bronchodilator nebulizer treatment',
            'oxygen' => 'Apply oxygen and position the client upright',
            'steroid' => 'Give oral corticosteroids as ordered',
            'peak' => 'Repeat peak flow in 20 minutes',
        ],
        'correct' => 'oxygen',
        'explanation' => 'Breathing impairment with hypoxemia requires immediate oxygen and upright positioning while preparing bronchodilator therapy. Treat hypoxemia before focusing solely on medication administration or reassessment timing.',
        'level_options' => [
            4 => [
                'neb' => 'Start continuous albuterol nebulization per protocol',
                'oxygen' => 'Apply oxygen and position the client upright',
                'steroid' => 'Administer IV methylprednisolone',
                'peak' => 'Obtain an arterial blood gas sample',
            ],
            5 => [
                'neb' => 'Teach proper metered-dose inhaler technique',
                'oxygen' => 'Apply oxygen and position the client upright',
                'steroid' => 'Review asthma trigger avoidance at discharge',
                'peak' => 'Schedule outpatient pulmonary function testing',
            ],
        ],
    ],
    [
        'title' => 'Unresponsive Client on the Unit',
        'scenario' => 'You find a client unresponsive in bed. There is no chest rise, carotid pulse is absent, and the call light is on the floor.',
        'question' => 'Which action should the PN take first?',
        'options' => [
            'cpr' => 'Start chest compressions and call for the code team',
            'airway' => 'Open the airway and deliver two rescue breaths',
            'defib' => 'Retrieve the automated external defibrillator and attach pads',
            'vitals' => 'Check blood pressure and glucose before acting',
        ],
        'correct' => 'cpr',
        'explanation' => 'When an unresponsive adult has absent pulse and no breathing (or only gasping), begin CPR immediately — compressions first in the healthcare provider sequence — and activate the emergency response system.',
        'level_options' => [
            4 => [
                'cpr' => 'Start chest compressions and call for the code team',
                'airway' => 'Insert an oropharyngeal airway and bag-mask ventilate',
                'defib' => 'Charge the manual defibrillator to 200 J',
                'vitals' => 'Obtain IV access and draw stat electrolytes',
            ],
            5 => [
                'cpr' => 'Start chest compressions and call for the code team',
                'airway' => 'Administer amiodarone IV push per ACLS',
                'defib' => 'Document the client\'s code status in the chart',
                'vitals' => 'Notify the family and offer spiritual support',
            ],
        ],
    ],
    [
        'title' => 'Tension Pneumothorax Signs',
        'scenario' => 'A client with a chest tube disconnected during transfer now has tracheal deviation, absent breath sounds on the right, distended neck veins, and SpO₂ 85%.',
        'question' => 'What is the priority action?',
        'options' => [
            'xray' => 'Transport the client for stat chest radiography',
            'needle' => 'Prepare for emergent needle decompression per protocol',
            'reconnect' => 'Reconnect the chest tube system and apply occlusive dressing if needed',
            'oxygen' => 'Apply high-flow oxygen and monitor closely',
        ],
        'correct' => 'reconnect',
        'explanation' => 'Restoring chest tube function addresses the underlying loss of negative intrapleural pressure. Apply high-flow oxygen and notify the provider immediately, but reconnecting or sealing the open pneumothorax is the urgent first step when disconnection caused the deterioration.',
        'level_options' => [
            4 => [
                'xray' => 'Obtain portable chest X-ray before any intervention',
                'needle' => 'Perform needle decompression in the second intercostal space',
                'reconnect' => 'Reconnect the chest tube system and apply occlusive dressing if needed',
                'oxygen' => 'Apply high-flow oxygen and monitor closely',
            ],
            5 => [
                'xray' => 'Complete a head-to-toe assessment and update the care plan',
                'needle' => 'Start IV heparin for suspected pulmonary embolism',
                'reconnect' => 'Reconnect the chest tube system and apply occlusive dressing if needed',
                'oxygen' => 'Encourage deep breathing and incentive spirometry',
            ],
        ],
    ],
    [
        'title' => 'GI Bleed with Hypotension',
        'scenario' => 'A client with a history of peptic ulcer disease vomits a large amount of bright red blood, becomes dizzy, heart rate 124/min, and blood pressure 88/54 mm Hg.',
        'question' => 'Which nursing action is the priority?',
        'options' => [
            'circulation' => 'Establish IV access, start isotonic fluids, and place the client supine with legs elevated',
            'airway' => 'Turn the client on the side and suction the oropharynx',
            'labs' => 'Draw hemoglobin, hematocrit, and type and screen',
            'ng' => 'Insert a nasogastric tube for lavage',
        ],
        'correct' => 'airway',
        'explanation' => 'Active hematemesis risks airway obstruction from aspiration. Position the client to protect the airway (recovery position, suction available) before focusing on volume resuscitation — ABC order applies even when circulation is compromised.',
        'level_options' => [
            4 => [
                'circulation' => 'Start a rapid IV fluid bolus and apply pressure monitoring',
                'airway' => 'Turn the client on the side and suction the oropharynx',
                'labs' => 'Send stat CBC, coagulation studies, and crossmatch',
                'ng' => 'Prepare for endoscopy and keep the client NPO',
            ],
            5 => [
                'circulation' => 'Transfuse packed red blood cells immediately without crossmatch',
                'airway' => 'Turn the client on the side and suction the oropharynx',
                'labs' => 'Review dietary teaching for ulcer prevention',
                'ng' => 'Administer proton pump inhibitor PO when stable',
            ],
        ],
    ],
    [
        'title' => 'Near-Drowning in Rehab',
        'scenario' => 'During pool therapy, a client is pulled from the water coughing weakly, lips cyanotic, SpO₂ 86%, and you hear faint wheezing bilaterally.',
        'question' => 'What should the PN do first?',
        'options' => [
            'dry' => 'Dry the client, provide warm blankets, and reassess in 10 minutes',
            'oxygen' => 'Apply supplemental oxygen and monitor airway and breathing',
            'cpr' => 'Begin chest compressions at 100–120/min',
            'transport' => 'Call EMS for immediate transport off-site',
        ],
        'correct' => 'oxygen',
        'explanation' => 'Near-drowning causes hypoxemia from aspirated fluid and bronchospasm. Breathing support with supplemental oxygen and continuous airway monitoring is the priority; CPR is not indicated while the client has a pulse and is breathing.',
        'level_options' => [
            4 => [
                'dry' => 'Complete a neurologic exam and Glasgow Coma Scale',
                'oxygen' => 'Apply supplemental oxygen and monitor airway and breathing',
                'cpr' => 'Initiate two-rescuer CPR with bag-mask ventilation',
                'transport' => 'Obtain a chest X-ray before any oxygen therapy',
            ],
            5 => [
                'dry' => 'Document pool incident report and resume therapy when SpO₂ normalizes',
                'oxygen' => 'Apply supplemental oxygen and monitor airway and breathing',
                'cpr' => 'Administer epinephrine for suspected anaphylaxis to pool chemicals',
                'transport' => 'Discharge the client with pool safety pamphlet',
            ],
        ],
    ],
]);
