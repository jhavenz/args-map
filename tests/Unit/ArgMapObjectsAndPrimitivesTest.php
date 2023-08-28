<?php

/** @noinspection PhpIllegalPsrClassPathInspection */

use Jhavenz\MappedArguments\ArgMap;
use Jhavenz\MappedArguments\Tests\Dummys\ObjectsAndPrimitives;

it('maps unordered arguments are passed in', function (array $args) {
    $map = ArgMap::create(ObjectsAndPrimitives::reflect(), $args);

    expect($map)->toBe([
        'files' => ObjectsAndPrimitives::files(),
        'isReadable' => ObjectsAndPrimitives::isReadable(),
        'filePath' => ObjectsAndPrimitives::filePath(),
    ]);
})->with(ObjectsAndPrimitives::unorderedKeys());

it('maps arguments when fractured maps are passed in', function (array $args) {
    $map = ArgMap::create(ObjectsAndPrimitives::reflect(), $args);

    expect($map)->toBe([
        'files' => ObjectsAndPrimitives::files(),
        'isReadable' => ObjectsAndPrimitives::isReadable(),
        'filePath' => ObjectsAndPrimitives::filePath(),
    ]);
})->with(ObjectsAndPrimitives::fracturedMap());
