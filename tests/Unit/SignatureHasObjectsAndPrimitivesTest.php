<?php

/** @noinspection PhpIllegalPsrClassPathInspection */

use Jhavenz\MappedArguments\ArgsMap;
use Jhavenz\MappedArguments\Tests\Dummys\ObjectsAndPrimitives;

it('maps unordered arguments are passed in', function (array $args) {
    $map = ArgsMap::fromCallable(ObjectsAndPrimitives::reflect(), $args);

    expect($map->create())->toBe([
        'files' => ObjectsAndPrimitives::files(),
        'isReadable' => ObjectsAndPrimitives::isReadOnly(),
        'filePath' => ObjectsAndPrimitives::filePath(),
    ]);
})->with(ObjectsAndPrimitives::unorderedKeys());

it('maps arguments when fractured maps are passed in', function (array $args) {
    $map = ArgsMap::fromCallable(ObjectsAndPrimitives::reflect(), $args);

    expect($map->create())->toBe([
        'files' => ObjectsAndPrimitives::files(),
        'isReadable' => ObjectsAndPrimitives::isReadOnly(),
        'filePath' => ObjectsAndPrimitives::filePath(),
    ]);
})->with(ObjectsAndPrimitives::fracturedMap());
