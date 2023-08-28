# map unordered or unnamed arguments to their respective parameter

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jhavenz/args-map.svg?style=flat-square)](https://packagist.org/packages/jhavenz/args-map)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/jhavenz/args-map/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/jhavenz/args-map/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/jhavenz/args-map/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/jhavenz/args-map/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/jhavenz/args-map.svg?style=flat-square)](https://packagist.org/packages/jhavenz/args-map)


You can install the package via composer:

```bash
composer require jhavenz/args-map
```

## Usage

```php
$mappedArguments = new Jhavenz\MappedArguments();
echo $mappedArguments->echoPhrase('Hello, Jhavenz!');
```

## Testing

```bash
composer test
```

## Contributing

Contributions welcome

## Security Vulnerabilities

[contact me](mailto://mail@jhavens.tech) to report security vulnerabilities.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
