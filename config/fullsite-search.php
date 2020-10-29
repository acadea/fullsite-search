<?php

return [
    // path to your models directory, relative to /app
    'model_path' => 'Models',

    'api' => [
        // enable api endpoint
        'disabled' => false,
        // the api endpoint uri
        'url' => '/api/site-search',
    ],

    // the result limit per model when conducting the search
    'search_limit_per_model' => 5,

    // you can put any models that you want to exclude from the search here
    'exclude' => [
        // example:
        // \App\Models\Comment::class
    ],

    // the number of neighbouring characters that you want to include in the match field of API response
    'buffer' => 10,

    // this is where you define where should the search result leads to.
    // the link should navigate to the resource view page
    // by default, we use `/<model-name>/<model-id>` , you can define anything here
    // We will replace `{id}` or `{ id }` with the model id
    'view_mapping' => [
        // \App\Models\Comment::class => '/comments/view/{id}'
    ],
];
