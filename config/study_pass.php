<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Study Pass email gate
    |--------------------------------------------------------------------------
    |
    | When enabled, preview guests must join Study Pass (free email signup)
    | after a few preview actions before continuing. Set STUDY_PASS_ENABLED=true
    | in .env to turn the popup back on.
    |
    */

    'enabled' => env('STUDY_PASS_ENABLED', false),

];
