<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Since you are using custom CORS middleware, you can safely disable
    | Laravel's default CORS by leaving paths empty or setting '*' for all.
    |
    */

    // Disable Laravel CORS middleware
    'paths' => [],

    'allowed_methods' => [],        // Not used
    'allowed_origins' => [],        // Not used
    'allowed_origins_patterns' => [],
    'allowed_headers' => [],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,

];
