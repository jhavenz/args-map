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
            yield 'unordered args 1' => fn () => [
                'filePath' => ObjectsAndPrimitives::filePath(),
                'isReadable' => ObjectsAndPrimitives::isReadOnly(),
                'files' => ObjectsAndPrimitives::files(),
            ];
            yield 'unordered args 2' => fn () => [
                'isReadable' => ObjectsAndPrimitives::isReadOnly(),
                'files' => ObjectsAndPrimitives::files(),
                'filePath' => ObjectsAndPrimitives::filePath(),
            ];
            yield 'unordered args 3' => fn () => [
                'files' => ObjectsAndPrimitives::files(),
                'filePath' => ObjectsAndPrimitives::filePath(),
                'isReadable' => ObjectsAndPrimitives::isReadOnly(),
            ];
        };
    }

    public static function fracturedMap(): Closure
    {
        return function () {
            yield 'fractured map 1' => fn () => [
                ObjectsAndPrimitives::filePath(),
                'isReadable' => ObjectsAndPrimitives::isReadOnly(),
                'files' => ObjectsAndPrimitives::files(),
            ];
            yield 'fractured map 2' => fn () => [
                'filePath' => ObjectsAndPrimitives::filePath(),
                ObjectsAndPrimitives::isReadOnly(),
                'files' => ObjectsAndPrimitives::files(),
            ];
            yield 'fractured map 3' => fn () => [
                ObjectsAndPrimitives::filePath(),
                ObjectsAndPrimitives::isReadOnly(),
                'files' => ObjectsAndPrimitives::files(),
            ];
            yield 'fractured map 4' => fn () => [
                ObjectsAndPrimitives::filePath(),
                ObjectsAndPrimitives::isReadOnly(),
                'files' => ObjectsAndPrimitives::files(),
            ];
        };
    }
}
