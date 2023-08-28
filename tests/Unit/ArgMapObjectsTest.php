<?php

/** @noinspection PhpIllegalPsrClassPathInspection */

use Jhavenz\MappedArguments\ArgMap;
use Jhavenz\MappedArguments\Tests\Dummys\Objects;

it('maps unordered arguments are passed in', function (array $args) {
    $map = ArgMap::create(Objects::reflect(), $args);

    expect($map)->toBe([
        'files' => Objects::files(),
        'modifiedAt' => Objects::modifiedAt(),
    ]);
})->with(Objects::unorderedKeys());

it('maps arguments when fractured maps are passed in', function (array $args) {
    $map = ArgMap::create(Objects::reflect(), $args);

    expect($map)->toMatchArray([
        'files' => Objects::files(),
        'modifiedAt' => Objects::modifiedAt(),
    ]);
})->with(Objects::fracturedMap());
