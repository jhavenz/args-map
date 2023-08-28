<?php

/** @noinspection PhpIllegalPsrClassPathInspection */

use Jhavenz\MappedArguments\ArgsMap;
use Jhavenz\MappedArguments\ArgumentMappingException;
use Jhavenz\MappedArguments\Tests\Dummys\AmbiguousObjects;
use Jhavenz\MappedArguments\Tests\TestCase;

it('orders the arguments correctly', function () {
    $map = ArgsMap::fromCallable(AmbiguousObjects::reflect(), [
        /** @param TestCase $this */
        'files' => $this->files(),
        'modifiedAt' => $this->modifiedAt(),
        'createdAt' => $this->createdAt(),
    ]);

    expect($map->create())->toBe([
        'createdAt' => $this->createdAt(),
        'files' => $this->files(),
        'modifiedAt' => $this->modifiedAt(),
    ]);
});

it('doesnt pass the same value to two different parameters if missing a different arg matches its type', function () {
    $map = ArgsMap::fromCallable(AmbiguousObjects::reflect(), [
        'files' => $this->files(),
        'createdAt' => $this->createdAt(),
    ]);

    expect($map->create())->toBe([
        'createdAt' => $this->createdAt(),
        'files' => $this->files(),
        'modifiedAt' => null,
    ]);
});

it('puts unnamed args in the right place', function (array $args) {
    $map = ArgsMap::fromCallable(AmbiguousObjects::reflect(), $args);

    expect($map->create())->toBe([
        'createdAt' => $this->createdAt(),
        'files' => $this->files(),
        'modifiedAt' => $this->modifiedAt(),
    ]);
})->with(function () {
    yield '1 unnamed arg' => fn () => [
        /** @param TestCase $this */
        'files' => $this->files(),
        $this->modifiedAt(),
        'createdAt' => $this->createdAt(),
    ];
    yield '2 unnamed args' => fn () => [
        /** @param TestCase $this */
        $this->files(),
        $this->modifiedAt(),
        'createdAt' => $this->createdAt(),
    ];
});

it('throws an error when 2 arguments are unnamed while sharing a type', function (array $args) {
    ArgsMap::fromCallable(AmbiguousObjects::reflect(), $args)->create();
})
->throws(
    ArgumentMappingException::class,
    "Ambiguous unnamed arguments found for the [createdAt] and [modifiedAt] parameters. They both share [DateTime] type signatures"
)
->with(function () {
    yield 'fractured map 1' => fn () => [
        AmbiguousObjects::files(),
        AmbiguousObjects::modifiedAt(),
        AmbiguousObjects::createdAt(),
    ];
    yield 'fractured map 2' => fn () => [
        AmbiguousObjects::modifiedAt(),
        AmbiguousObjects::createdAt(),
        AmbiguousObjects::files(),
    ];
    yield 'fractured map 3' => fn () => [
        'files' => AmbiguousObjects::files(),
        AmbiguousObjects::modifiedAt(),
        AmbiguousObjects::createdAt(),
    ];
});
