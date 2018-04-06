<?php

return [

    'allow_origins' => [
        '*',
    ],

    'allow_methods' => [
        'POST',
        'GET',
        'OPTIONS',
        'PUT',
        'PATCH',
        'DELETE',
    ],

    'allow_headers' => [
        'Content-Type',
        'X-Auth-Token',
        'Origin',
        'Authorization',
    ],

    /*
     * Preflight request will respond with value for the max age header.
     */
    'max_age'       => 60 * 60 * 24,
];
