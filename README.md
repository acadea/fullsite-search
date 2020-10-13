# Laravel package to perform full site search based on Laravel Scout

[![Latest Version on Packagist](https://img.shields.io/packagist/v/acadea/fullsite-search.svg?style=flat-square)](https://packagist.org/packages/acadea/fullsite-search)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/acadea/fullsite-search/run-tests?label=tests)](https://github.com/acadea/fullsite-search/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/acadea/fullsite-search.svg?style=flat-square)](https://packagist.org/packages/acadea/fullsite-search)


This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Support us


## Installation

You can install the package via composer:

```bash
composer require acadea/fullsite-search
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --provider="Acadea\FullSite\FullSiteServiceProvider" --tag="migrations"
php artisan migrate
```

You can publish the config file with:
```bash
php artisan vendor:publish --provider="Acadea\FullSite\FullSiteServiceProvider" --tag="config"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

``` php
$fullsite-search = new Acadea\FullSite();
echo $fullsite-search->echoPhrase('Hello, Acadea!');
```

## Testing

``` bash
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
