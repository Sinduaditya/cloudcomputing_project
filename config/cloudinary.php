<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Cloudinary Configuration
    |--------------------------------------------------------------------------
    |
    | These configuration values are used for Cloudinary integration.
    |
    */

    'cloud_name' => env('CLOUDINARY_CLOUD_NAME', 'dw07rmsbq'),
    'api_key' => env('CLOUDINARY_API_KEY', '652111313776754'),
    'api_secret' => env('CLOUDINARY_API_SECRET', 'R9y6XpspgXvxDvekMNyXr3NTJRw'),

    'url' => [
        'secure' => true,
    ],

    'uploads' => [
        'folder' => 'downloads',
        'resource_types' => [
            'image' => ['jpg', 'jpeg', 'png', 'gif'],
            'video' => ['mp4', 'mov', 'avi', 'mp3'],
            'raw' => ['pdf', 'docx', 'xlsx'],
        ],
    ],
];
