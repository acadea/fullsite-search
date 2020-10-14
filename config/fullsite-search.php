<?php

return [

    'model_path' => 'Models',

    'api' => [
        // enable api endpoint
        'disabled' => false,
        'url' => '/api/site-search',
    ],

    'exclude' => [
        // example:
        // \App\Models\Comment::class
    ],

    // buffer text around the match
    'buffer' => 10,

    'view_mapping' => [
        // \App\Models\Comment::class => '/comments/view/{id}'
    ],
];
