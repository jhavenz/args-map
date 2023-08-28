<?php

declare(strict_types=1);

namespace Jhavenz\MappedArguments\Tests\Dummys;

use Closure;

class Primitives
{
    use Shared;

    public function testMethod(int $modifiedAt, bool $isReadable = false, string|null $filePath = null): void
    {
        //
    }

    public static function unorderedKeys(): Closure
    {
        return function () {
            yield fn () => [
                'filePath' => Primitives::filePath(),
                'isReadable' => Primitives::isReadable(),
                'modifiedAt' => Primitives::modifiedAt(),
            ];
            yield fn () => [
                'isReadable' => Primitives::isReadable(),
                'modifiedAt' => Primitives::modifiedAt(),
                'filePath' => Primitives::filePath(),
            ];
            yield fn () => [
                'modifiedAt' => Primitives::modifiedAt(),
                'filePath' => Primitives::filePath(),
                'isReadable' => Primitives::isReadable(),
            ];
        };
    }

    public static function fracturedMap(): Closure
    {
        return function () {
            yield fn () => [
                Primitives::filePath(),
                'isReadable' => Primitives::isReadable(),
                Primitives::modifiedAt(),
            ];
            yield fn () => [
                'filePath' => Primitives::filePath(),
                Primitives::modifiedAt(),
                Primitives::isReadable(),
            ];
            yield fn () => [
                'filePath' => Primitives::filePath(),
                'isReadable' => Primitives::isReadable(),
                Primitives::modifiedAt(),
            ];
            yield fn () => [
                'modifiedAt' => Primitives::modifiedAt(),
                'filePath' => Primitives::filePath(),
                Primitives::isReadable(),
            ];
            yield fn () => [
                Primitives::isReadable(),
                Primitives::filePath(),
                'modifiedAt' => Primitives::modifiedAt(),
            ];
            yield fn () => [
                Primitives::filePath(),
                Primitives::modifiedAt(),
                Primitives::isReadable(),
            ];
        };
    }
}
