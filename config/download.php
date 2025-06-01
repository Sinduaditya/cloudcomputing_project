<?php
// filepath: f:\UGM\cloudcomputing\cloudcomputing_project\config\download.php

return [
    /*
    |--------------------------------------------------------------------------
    | Download Service Configuration
    |--------------------------------------------------------------------------
    */

    // Temporary storage path for downloaded files
    'temp_path' => storage_path('app/downloads/temp'),

    // Maximum file size (in bytes) - 500MB default
    'max_file_size' => env('MAX_DOWNLOAD_SIZE', 500 * 1024 * 1024),

    // Download timeout in seconds
    'timeout' => env('DOWNLOAD_TIMEOUT', 300),

    // Default format and quality settings
    'default_format' => 'mp4',
    'default_quality' => '720p',

    // Supported platforms configuration
    'platforms' => [
        'tiktok' => [
            'formats' => ['mp4', 'mp3'],
            'qualities' => ['360p', '480p', '720p'],
            'enabled' => true,
        ],
        'youtube' => [
            'formats' => ['mp4', 'mp3'],
            'qualities' => ['360p', '480p', '720p', '1080p'],
            'enabled' => true,
        ],
        'instagram' => [
            'formats' => ['mp4', 'mp3'],
            'qualities' => ['360p', '480p', '720p'],
            'enabled' => true,
        ],
    ],

    // API keys and credentials for download services
    // Add any API keys you need for external services here
    'api_keys' => [
        'tikapi' => env('TIKAPI_KEY', ''),
        'rapidapi' => env('RAPIDAPI_KEY', ''),
    ],

    'cloudinary_threshold_mb' => env('CLOUDINARY_THRESHOLD_MB', 50),
    'always_use_cloudinary' => env('ALWAYS_USE_CLOUDINARY', false),
    'mb_per_token' => env('TOKEN_MB_RATIO', 10),
    'min_tokens' => env('TOKEN_MIN_BALANCE', 1),
];
