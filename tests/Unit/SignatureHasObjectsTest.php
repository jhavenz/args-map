<?php

/** @noinspection PhpIllegalPsrClassPathInspection */

use Jhavenz\MappedArguments\ArgsMap;
use Jhavenz\MappedArguments\Tests\Dummys\Objects;

it('maps unordered arguments are passed in', function (array $args) {
    $map = ArgsMap::fromCallable(Objects::reflect(), $args);

    expect($map->create())->toBe([
        'files' => Objects::files(),
        'modifiedAt' => Objects::modifiedAt(),
    ]);
})->with(Objects::unorderedKeys());

it('maps arguments when fractured maps are passed in', function (array $args) {
    $map = ArgsMap::fromCallable(Objects::reflect(), $args);

    expect($map->create())->toMatchArray([
        'files' => Objects::files(),
        'modifiedAt' => Objects::modifiedAt(),
    ]);
})->with(Objects::fracturedMap());
