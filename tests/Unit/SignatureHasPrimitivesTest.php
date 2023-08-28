<?php

/** @noinspection PhpIllegalPsrClassPathInspection */

use Jhavenz\MappedArguments\ArgsMap;
use Jhavenz\MappedArguments\Tests\Dummys\Primitives;

it('maps unordered arguments to the correct order', function (array $args) {
    $map = ArgsMap::fromCallable(Primitives::reflect(), $args);

    expect($map->create())->toBe([
        'modifiedAt' => Primitives::modifiedAt(),
        'isReadable' => Primitives::isReadOnly(),
        'filePath' => Primitives::filePath(),
    ]);
})->with(Primitives::unorderedKeys());

it('maps arguments when a fractured map is passed in', function (array $args) {
    $map = ArgsMap::fromCallable(Primitives::reflect(), $args);

    expect($map->create())->toMatchArray([
        'modifiedAt' => Primitives::modifiedAt(),
        'isReadable' => Primitives::isReadOnly(),
        'filePath' => Primitives::filePath(),
    ]);
})->with(Primitives::fracturedMap());
