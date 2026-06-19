<?php

/**
 * SOAP charting levels 2–5. Each level has 10 scenarios with increasing
 * sentence count, distractors, and borderline S/O classification.
 */
return [
    2 => require __DIR__.'/soap_levels/level_2.php',
    3 => require __DIR__.'/soap_levels/level_3.php',
    4 => require __DIR__.'/soap_levels/level_4.php',
    5 => require __DIR__.'/soap_levels/level_5.php',
];
