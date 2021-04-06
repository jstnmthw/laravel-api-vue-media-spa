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
    'excluded_cats' => env('MEDIA_EXCLUDED_CATEGORIES', ''),
    'media_seed' => env('MEDIA_SEED', 10),
    'media_deleted_csv_url' => env('MEDIA_DELETED_FILE_URL', ''),
    'media_csv_url' => env('MEDIA_FILE_URL', ''),
    'media_new_fn' => env('MEDIA_NEW_FILE_NAME', ''),
    'media_new_unzipped' => env('MEDIA_NEW_FILE_UNZIPPED', ''),
];
