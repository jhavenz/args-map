<?php

declare(strict_types=1);

namespace Jhavenz\MappedArguments\Tests\Dummys;

use Closure;
use Illuminate\Contracts\Filesystem\Filesystem as IFilesystem;
use Stringable;

class ObjectsAndPrimitives
{
    use Shared;

    public function testMethod(IFilesystem $files, bool $isReadable = false, Stringable|string|null $filePath = null): void
    {
        //
    }

    public static function filePath(): string|Stringable
    {
        return static::$filePath ??= new class() implements \Stringable {
            public function __toString()
            {
                return 'some/file/path.txt';
            }
        };
    }

    public static function unorderedKeys(): Closure
    {
        return function () {
            yield fn () => [
                'filePath' => ObjectsAndPrimitives::filePath(),
                'isReadable' => ObjectsAndPrimitives::isReadable(),
                'files' => ObjectsAndPrimitives::files(),
            ];
            yield fn () => [
                'isReadable' => ObjectsAndPrimitives::isReadable(),
                'files' => ObjectsAndPrimitives::files(),
                'filePath' => ObjectsAndPrimitives::filePath(),
            ];
            yield fn () => [
                'files' => ObjectsAndPrimitives::files(),
                'filePath' => ObjectsAndPrimitives::filePath(),
                'isReadable' => ObjectsAndPrimitives::isReadable(),
            ];
        };
    }

    public static function fracturedMap(): Closure
    {
        return function () {
            yield fn () => [
                ObjectsAndPrimitives::filePath(),
                'isReadable' => ObjectsAndPrimitives::isReadable(),
                'files' => ObjectsAndPrimitives::files(),
            ];
            yield fn () => [
                'filePath' => ObjectsAndPrimitives::filePath(),
                ObjectsAndPrimitives::isReadable(),
                'files' => ObjectsAndPrimitives::files(),
            ];
            yield fn () => [
                ObjectsAndPrimitives::filePath(),
                ObjectsAndPrimitives::isReadable(),
                'files' => ObjectsAndPrimitives::files(),
            ];
            yield fn () => [
                ObjectsAndPrimitives::filePath(),
                ObjectsAndPrimitives::isReadable(),
                'files' => ObjectsAndPrimitives::files(),
            ];
        };
    }
}
