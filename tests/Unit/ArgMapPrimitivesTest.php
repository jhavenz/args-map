<?php

/** @noinspection PhpIllegalPsrClassPathInspection */

use Jhavenz\MappedArguments\ArgMap;
use Jhavenz\MappedArguments\Tests\Dummys\Primitives;

it('maps unordered arguments to the correct order', function (array $args) {
    $map = ArgMap::create(Primitives::reflect(), $args);

    expect($map)->toBe([
        'modifiedAt' => Primitives::modifiedAt(),
        'isReadable' => Primitives::isReadable(),
        'filePath' => Primitives::filePath(),
    ]);
})->with(Primitives::unorderedKeys());

it('maps arguments when a fractured map is passed in', function (array $args) {
    $map = ArgMap::create(Primitives::reflect(), $args);

    expect($map)->toMatchArray([
        'modifiedAt' => Primitives::modifiedAt(),
        'isReadable' => Primitives::isReadable(),
        'filePath' => Primitives::filePath(),
    ]);
})->with(Primitives::fracturedMap());
