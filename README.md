# map unordered or unnamed arguments to their respective parameter

You can install the package via composer:

```bash
composer require jhavenz/args-map
```

## Usage

The examples below will use refer to this example class:
```php
class SomeClass
{
    public function someMethod(DateTimeInterface $createdAt, IFilesystem $files, ?DateTimeInterface $modifiedAt = null): void
    {
        //
    }
}
```


Scenario 1 - some args are missing their name
```php
$map = ArgsMap::fromCallable([SomeClass::class, 'someMethod'], [
    'files' => app('filesystem.disk'),
    'modifiedAt' => yesterday(),
    lastMonth(), 
]);

// The 3rd param is inferred as the $createdAt argument
expect($map->create())->toBe([
    'createdAt' => lastMonth(),
    'files' => app('filesystem.disk'),
    'modifiedAt' => yesterday(),
]);
```

#Scenario 2 - some args are missing (and may be missing their name)
```php
$map = ArgsMap::fromCallable([SomeClass::class, 'someMethod'], [
    'files' => app('filesystem.disk'),
    lastMonth(),
]);

// The 3rd param receives its default value,
// while the unnamed (required) param is inferred
// as the lastMonth() value given, a logical assumption...
expect($map->create())->toBe([
    'createdAt' => lastMonth(),
    'files' => app('filesystem.disk'),
    'modifiedAt' => null,
]);
```

#Scenario 3 - ambiguous args still throw an error
```php
$map = ArgsMap::fromCallable([SomeClass::class, 'someMethod'], [
    app('filesystem.disk'),
    lastMonth(), 
    yesterday(),
]);

// While the app('filesystem.disk') value given
// can be inferred (since it's the type signature
// only has one corresponding match), the last two
// arguments still leave room for ambiguity, so an
// error will be thrown
expect($map->create())->toThrow(ArgumentMappingException::class);
```

## Testing

```bash
composer test
```

## Contributing

Contributions welcome

## Security Vulnerabilities

contact me at mail@jhavens.tech to report security vulnerabilities.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
