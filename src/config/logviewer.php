<?php

return [
     /*
     |--------------------------------------------------------------------------
     | Pattern and storage path settings
     |--------------------------------------------------------------------------
     |
     | The env key for pattern and storage path with a default value
     |
     */
    'pattern' => env('LOGVIEWER_PATTERN', '*.log'),
    'storage_path' => env('LOGVIEWER_STORAGE_PATH', 'logs'),
    'server_paging' => env('LOGVIEWER_SERVER_PAGING', false),
];
