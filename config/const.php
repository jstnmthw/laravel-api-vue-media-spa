<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Constant Variables
    |--------------------------------------------------------------------------
    |
    | Here you may configure custom constants specific to your app.
    |
    */
    // Media categories exempt from main listings
    'excluded_cats' => env('EXCLUDED_CATEGORIES', ''),
    'media_seed' => env('MEDIA_SEED', 10),
];
