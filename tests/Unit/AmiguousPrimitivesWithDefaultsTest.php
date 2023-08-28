<?php

/** @noinspection PhpIllegalPsrClassPathInspection */

use Jhavenz\MappedArguments\ArgsMap;
use Jhavenz\MappedArguments\ArgumentMappingException;
use Jhavenz\MappedArguments\Tests\Dummys\AmbiguousPrimitivesWithDefaults;

it('orders the arguments correctly', function () {
    $map = ArgsMap::fromCallable(AmbiguousPrimitivesWithDefaults::reflect(), [
        'isWriteOnly' => $this->isWriteOnly(),
        'isReadOnly' => $this->isReadOnly(),
        'modifiedAt' => $this->modifiedAtTimestamp(),
    ]);

    expect($map->create())->toBe([
        'modifiedAt' => $this->modifiedAtTimestamp(),
        'isReadOnly' => $this->isReadOnly(),
        'isWriteOnly' => $this->isWriteOnly(),
    ]);
});

it('doesnt pass the same value to two different parameters if missing a different arg matches its type', function () {
    $map = ArgsMap::fromCallable(AmbiguousPrimitivesWithDefaults::reflect(), [
        'isWriteOnly' => $this->isWriteOnly(),
        'modifiedAt' => $this->modifiedAtTimestamp(),
    ]);

    expect($map->create())->toBe([
        'modifiedAt' => $this->modifiedAtTimestamp(),
        'isReadOnly' => null,
        'isWriteOnly' => $this->isWriteOnly(),
    ]);
});

it('puts unnamed args in the right place', function (array $args) {
    $map = ArgsMap::fromCallable(AmbiguousPrimitivesWithDefaults::reflect(), $args);

    [$modifiedAt, $isReadOnly, $isWriteOnly] = array_values($args);

    expect($map->create())->toBe([
        'modifiedAt' => $modifiedAt,
        // default params should be given if null is passed...
        'isReadOnly' => is_null($isReadOnly) ? false : $isReadOnly,
        'isWriteOnly' => is_null($isWriteOnly) ? false : $isWriteOnly,
    ]);
})
->with(function () {
    yield 'unnamed args 1' => fn () => [
        $this->modifiedAtTimestamp(),
        'isReadOnly' => null,
        'isWriteOnly' => $this->isWriteOnly(),
        'a',
    ];
    yield 'unnamed args 2' => fn () => [
        $this->modifiedAtTimestamp(),
        null,
        'isWriteOnly' => $this->isWriteOnly(),
        'b',
    ];
    yield 'unnamed args 3' => fn () => [
        $this->modifiedAtTimestamp(),
        'isReadOnly' => null,
        $this->isWriteOnly(),
        'c',
    ];
    yield 'unnamed args 4' => fn () => [
        'modifiedAt' => $this->modifiedAtTimestamp(),
        'isReadOnly' => $this->isReadOnly(),
        $this->isWriteOnly(),
        'd',
    ];
    yield 'unnamed args 5' => fn () => [
        $this->modifiedAtTimestamp(),
        'isReadOnly' => $this->isReadOnly(),
        $this->isWriteOnly(),
        'e',
    ];
    yield 'unnamed args 6' => fn () => [
        $this->modifiedAtTimestamp(),
        'isReadOnly' => $this->isReadOnly(),
        'isWriteOnly' => $this->isWriteOnly(),
        'f',
    ];
});

it('throws an error when 2 arguments are unnamed and the invalid types are given', function (array $args) {
    $map = ArgsMap::fromCallable(AmbiguousPrimitivesWithDefaults::reflect(), $args);

    [$modifiedAt, $isReadOnly, $isWriteOnly] = array_values($args);

    expect($map->create())->toBe([
        'modifiedAt' => $modifiedAt,
        'isReadOnly' => is_null($isReadOnly) ? false : $isReadOnly,
        'isWriteOnly' => $isWriteOnly,
    ]);
})
->with(function () {
    yield 'unnamed args 1' => fn () => [
        $this->modifiedAtTimestamp(),
        null, // <- incorrect type
        $this->isWriteOnly(),
        'a',
    ];
    yield 'unnamed args 2' => fn () => [
        'modifiedAt' => $this->modifiedAtTimestamp(),
        $this->isReadOnly(),
        'foo', // <- incorrect type
        'b',
    ];
})
->throws(
    ArgumentMappingException::class,
    "Ambiguous unnamed arguments found for the [isReadOnly] and [isWriteOnly] parameters. They both share [bool] type signatures"
);
