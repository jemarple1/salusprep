<?php

return [

    'regular_price_cents' => 899,

    'sale_price_cents' => 599,

    /*
    | Sale runs through end of day in the app timezone (config/app.php).
    | After this datetime, pricing returns to the regular amount.
    */
    'sale_ends_at' => env('PRICING_SALE_ENDS_AT', '2026-06-30 23:59:59'),

    /*
    | Customer-facing Stripe promotion code applied automatically at checkout
    | during the sale period. Not shown anywhere on the site.
    */
    'stripe_promotion_code' => env('STRIPE_PROMOTION_CODE', 'JunePrep'),

    /*
    | Optional Stripe promotion code ID (promo_...) to skip API lookup.
    */
    'stripe_promotion_id' => env('STRIPE_PROMOTION_ID'),

];
