# Laravel Full Site Search

[![Latest Version on Packagist](https://img.shields.io/packagist/v/acadea/fullsite-search.svg?style=flat-square)](https://packagist.org/packages/acadea/fullsite-search)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/acadea/fullsite-search/run-tests?label=tests)](https://github.com/acadea/fullsite-search/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/acadea/fullsite-search.svg?style=flat-square)](https://packagist.org/packages/acadea/fullsite-search)


Laravel package to perform full site search based on Laravel Scout. Bringing the `search everything` box to live in a laravel app.

## Support us

Learn the idea behind this package in:

[Medium Blog]()

[Youtube Lesson]()

Follow us on Youtube: [Acadea.io](https://www.youtube.com/channel/UCU5RsUGkVcPM9QvFHyKm1OQ)

## Quick Start

```php
// Expect a collection of models in the search result
$results = \Acadea\FullSite\FullSiteSearch::search('in');
```

Each model returned has 3 additional attributes:
* a. `match` -- the match + neighbouring text found from our DB records
* b. `model` -- the related model name
* c. `view_link` -- the URL for the user to navigate in the frontend to view the resource

To return the results as an API response:
```php
// Controller
return \Acadea\FullSite\Resources\SiteSearchResource::collection($results);
```

For your convenience, we have bootstrapped the API endpoint for you. You can disable this in the config file.
Just set the `fullsite-search.api.disabled` config to `true`.

#### Example:

URL: `/api/site-search?search=in`

We get:

```json
{
    "data": [
        {
            "id": 2,
            "match": "...ER happen in a frighte...",
            "model": "Post",
            "view_link": "http://127.0.0.1:8000/posts/2"
        },
        {
            "id": 4,
            "match": "Drawling, Stretching, ...",
            "model": "Post",
            "view_link": "http://127.0.0.1:8000/posts/4"
        },
        {
            "id": 6,
            "match": "...ed to her in the dista...",
            "model": "Post",
            "view_link": "http://127.0.0.1:8000/posts/6"
        }
    ]
}
```

## Installation

You can install the package via composer:

```bash
composer require acadea/fullsite-search
```


You can publish the config file with:
```bash
php artisan vendor:publish --provider="Acadea\FullSite\FullSiteServiceProvider" --tag="config"
```

This is the contents of the published config file:

```php
return [
    // path to your models directory, relative to /app
    'model_path' => 'Models',

    'api' => [
        // enable api endpoint
        'disabled' => false,
        // the api endpoint uri
        'url' => '/api/site-search',
    ],

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
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [sam-ngu](https://github.com/sam-ngu)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
