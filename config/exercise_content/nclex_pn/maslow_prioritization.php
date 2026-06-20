<?php

require_once __DIR__.'/helpers.php';

if (! function_exists('nclex_maslow_bank')) {
    /**
     * Build Maslow priority scenario with 4 items (L1–2) or 5 items (L3–5).
     *
     * @param  array<string, mixed>  $base
     * @return array<string, mixed>
     */
    function nclex_maslow_bank(array $base, int $level, int $index): array
    {
    $items = $base['items'];
    $correctOrder = $base['correct_order'];

    if ($level <= 2) {
        $correctOrder = array_slice($correctOrder, 0, 4);
        $keepIds = array_flip($correctOrder);
        $items = array_values(array_filter($items, fn (array $item) => isset($keepIds[$item['id']])));
    }

    return nclex_enrich([
        'title' => $base['title'],
        'scenario' => $base['scenario'],
        'explanation' => $base['explanation'],
        'items' => $items,
        'correct_order' => $correctOrder,
    ], $level, $index);
    }
}

return nclex_levels([
    fn (int $level, int $index) => nclex_maslow_bank([
        'title' => 'Post-op Pain vs Comfort Requests',
        'scenario' => 'You enter the room of a client 6 hours post–appendectomy who is grimacing, guarding the abdomen, and also asks for extra blankets because the room feels cold.',
        'explanation' => 'Physiological needs (pain control affecting breathing and recovery) take priority over comfort preferences. Safety follows physiology; psychosocial needs come after acute physical needs are addressed.',
        'items' => [
            ['id' => 'pain', 'text' => 'Administer prescribed analgesic and reassess pain level'],
            ['id' => 'incision', 'text' => 'Inspect surgical incision for bleeding or dehiscence'],
            ['id' => 'blanket', 'text' => 'Provide warm blanket and adjust room temperature'],
            ['id' => 'phone', 'text' => 'Assist client to charge phone and call family'],
            ['id' => 'tv', 'text' => 'Help client find a distracting television program'],
        ],
        'correct_order' => ['pain', 'incision', 'blanket', 'phone', 'tv'],
    ], $level, $index),

    fn (int $level, int $index) => nclex_maslow_bank([
        'title' => 'Hypoglycemia on Med-Surg',
        'scenario' => 'A diabetic client is diaphoretic, tremulous, glucose 54 mg/dL, and states, "I missed lunch because I didn\'t like the food." The client also wants help combing hair before visitors arrive.',
        'explanation' => 'Treat hypoglycemia immediately — a physiological survival need. Grooming and visitor concerns are lower on Maslow\'s hierarchy once the client is safe.',
        'items' => [
            ['id' => 'glucose', 'text' => 'Give 15 g fast-acting carbohydrate if alert and able to swallow'],
            ['id' => 'recheck', 'text' => 'Recheck blood glucose in 15 minutes per hypoglycemia protocol'],
            ['id' => 'notify', 'text' => 'Notify provider if glucose remains low after treatment'],
            ['id' => 'groom', 'text' => 'Assist with hair grooming before visitors'],
            ['id' => 'menu', 'text' => 'Review tomorrow\'s menu selections with dietary'],
        ],
        'correct_order' => ['glucose', 'recheck', 'notify', 'groom', 'menu'],
    ], $level, $index),

    fn (int $level, int $index) => nclex_maslow_bank([
        'title' => 'Dyspnea and Anxiety',
        'scenario' => 'A client with COPD is sitting upright, using accessory muscles, SpO₂ 88% on 2 L, and says anxiously, "Am I going to die?"',
        'explanation' => 'Impaired gas exchange is an immediate physiological priority. Reassurance addresses psychosocial anxiety but only after breathing and oxygenation interventions are initiated.',
        'items' => [
            ['id' => 'oxygen', 'text' => 'Increase oxygen per protocol and notify provider'],
            ['id' => 'position', 'text' => 'Position in high Fowler and encourage pursed-lip breathing'],
            ['id' => 'meds', 'text' => 'Administer ordered bronchodilator treatment'],
            ['id' => 'reassure', 'text' => 'Stay with client and provide calm, factual reassurance'],
            ['id' => 'spiritual', 'text' => 'Offer chaplain visit for existential concerns'],
        ],
        'correct_order' => ['oxygen', 'position', 'meds', 'reassure', 'spiritual'],
    ], $level, $index),

    fn (int $level, int $index) => nclex_maslow_bank([
        'title' => 'Homeless Client Admission',
        'scenario' => 'A client admitted with cellulitis of the leg also has unwashed clothing, strong body odor, and says, "I haven\'t eaten in two days." Temperature is 101.2°F (38.4°C).',
        'explanation' => 'Nutrition and infection (physiological) precede hygiene and dignity concerns. Psychosocial stigma must not delay treatment of fever and malnutrition.',
        'items' => [
            ['id' => 'infection', 'text' => 'Administer ordered antibiotics and monitor for sepsis signs'],
            ['id' => 'nutrition', 'text' => 'Provide meal and fluids; assess swallow and appetite'],
            ['id' => 'pain', 'text' => 'Assess leg pain and elevate extremity as ordered'],
            ['id' => 'hygiene', 'text' => 'Offer bed bath and clean hospital gown'],
            ['id' => 'social', 'text' => 'Contact social work for discharge housing resources'],
        ],
        'correct_order' => ['infection', 'nutrition', 'pain', 'hygiene', 'social'],
    ], $level, $index),

    fn (int $level, int $index) => nclex_maslow_bank([
        'title' => 'Confused Client Near Restraints',
        'scenario' => 'An older adult with delirium tries to climb out of bed despite a low bed and alarm. IV line is at risk of dislodgment. Family asks you to sit and reminisce about old photos.',
        'explanation' => 'Safety (preventing falls and line loss) is priority when physiological stability is at risk. Restraints require provider order and are last resort after less restrictive safety measures.',
        'items' => [
            ['id' => 'safety', 'text' => 'Implement fall precautions: stay with client, call bell, bed low, wheels locked'],
            ['id' => 'iv', 'text' => 'Secure IV site and monitor for infiltration'],
            ['id' => 'reorient', 'text' => 'Reorient to person, place, and time with calm redirection'],
            ['id' => 'family', 'text' => 'Encourage family presence if it soothes the client safely'],
            ['id' => 'photos', 'text' => 'Review photo album when client is safe in chair with supervision'],
        ],
        'correct_order' => ['safety', 'iv', 'reorient', 'family', 'photos'],
    ], $level, $index),

    fn (int $level, int $index) => nclex_maslow_bank([
        'title' => 'Labor Client Multiple Requests',
        'scenario' => 'A client in active labor reports intense contractions every 2 minutes, also feels cold, has not voided in 4 hours, and asks for lip balm because lips are dry.',
        'explanation' => 'Fetal and maternal physiological monitoring during labor precedes comfort measures. Urinary retention can impede descent; thermoregulation and lip care are lower priority.',
        'items' => [
            ['id' => 'fhr', 'text' => 'Assess fetal heart rate pattern and contraction frequency'],
            ['id' => 'void', 'text' => 'Encourage voiding or catheterize per protocol if unable'],
            ['id' => 'comfort', 'text' => 'Provide ordered analgesia or nonpharmacologic pain relief'],
            ['id' => 'warmth', 'text' => 'Offer warm blanket for chills between contractions'],
            ['id' => 'lip', 'text' => 'Apply lip balm and offer ice chips if not NPO restricted'],
        ],
        'correct_order' => ['fhr', 'void', 'comfort', 'warmth', 'lip'],
    ], $level, $index),

    fn (int $level, int $index) => nclex_maslow_bank([
        'title' => 'Psychiatric Unit Meal Refusal',
        'scenario' => 'A client with depression has not eaten in 24 hours, blood pressure 98/60, and says, "Leave me alone." The client also requests a private room change because of a noisy roommate.',
        'explanation' => 'Nutrition and hemodynamic stability are physiological priorities on an inpatient psych unit. Environmental preferences and privacy requests follow stabilization.',
        'items' => [
            ['id' => 'intake', 'text' => 'Offer high-calorie small meal and monitor intake'],
            ['id' => 'vitals', 'text' => 'Monitor orthostatic vital signs and hydration status'],
            ['id' => 'suicide', 'text' => 'Assess safety per unit protocol and maintain observation level'],
            ['id' => 'room', 'text' => 'Discuss room change with charge nurse when feasible'],
            ['id' => 'group', 'text' => 'Encourage attendance at afternoon group therapy'],
        ],
        'correct_order' => ['intake', 'vitals', 'suicide', 'room', 'group'],
    ], $level, $index),

    fn (int $level, int $index) => nclex_maslow_bank([
        'title' => 'Pediatric Asthma and Stuffed Animal',
        'scenario' => 'A 6-year-old with wheezing, retractions, and SpO₂ 90% clutches a stuffed animal and cries for a parent who stepped out for coffee.',
        'explanation' => 'Airway and breathing interventions come first. Emotional support and attachment objects help once acute respiratory distress is addressed.',
        'items' => [
            ['id' => 'breathing', 'text' => 'Administer ordered bronchodilator and apply oxygen'],
            ['id' => 'monitor', 'text' => 'Continuous pulse oximetry and respiratory assessment'],
            ['id' => 'parent', 'text' => 'Contact parent and keep stuffed animal within sight'],
            ['id' => 'distraction', 'text' => 'Use age-appropriate distraction during treatments'],
            ['id' => 'stickers', 'text' => 'Offer reward sticker after breathing improves'],
        ],
        'correct_order' => ['breathing', 'monitor', 'parent', 'distraction', 'stickers'],
    ], $level, $index),

    fn (int $level, int $index) => nclex_maslow_bank([
        'title' => 'Burn Client Multiple Needs',
        'scenario' => 'A client with partial-thickness burns on both arms reports thirst, chills, and anxiety about permanent scarring. IV fluids are running wide open per burn protocol.',
        'explanation' => 'Fluid resuscitation and thermoregulation address physiological burn priorities. Body image concerns are important but follow stabilization.',
        'items' => [
            ['id' => 'fluids', 'text' => 'Monitor IV fluid rate, urine output, and perfusion'],
            ['id' => 'warmth', 'text' => 'Maintain warm environment and pain control as ordered'],
            ['id' => 'wound', 'text' => 'Assess burn wounds for progression and infection'],
            ['id' => 'thirst', 'text' => 'Provide oral fluids when bowel sounds return and allowed'],
            ['id' => 'body', 'text' => 'Acknowledge fears about scarring and offer resources'],
        ],
        'correct_order' => ['fluids', 'warmth', 'wound', 'thirst', 'body'],
    ], $level, $index),

    fn (int $level, int $index) => nclex_maslow_bank([
        'title' => 'End-of-Life Comfort Care',
        'scenario' => 'A hospice client has labored breathing with gurgling secretions, cool extremities, and family at bedside asking for clergy and favorite music.',
        'explanation' => 'Physiological comfort at end of life (airway secretions, positioning for breathing) precedes spiritual and emotional legacy activities, though all are valued in holistic care.',
        'items' => [
            ['id' => 'secretions', 'text' => 'Position for airway drainage and provide oral suction as needed'],
            ['id' => 'position', 'text' => 'Reposition for comfort and apply lubricant to dry lips'],
            ['id' => 'family', 'text' => 'Support family presence and explain expected changes'],
            ['id' => 'clergy', 'text' => 'Contact spiritual care per family request'],
            ['id' => 'music', 'text' => 'Play preferred music at low volume when breathing is eased'],
        ],
        'correct_order' => ['secretions', 'position', 'family', 'clergy', 'music'],
    ], $level, $index),
]);
