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
            yield 'unordered args 1' => fn () => [
                'filePath' => Primitives::filePath(),
                'isReadable' => Primitives::isReadOnly(),
                'modifiedAt' => Primitives::modifiedAt(),
            ];
            yield 'unordered args 2' => fn () => [
                'isReadable' => Primitives::isReadOnly(),
                'modifiedAt' => Primitives::modifiedAt(),
                'filePath' => Primitives::filePath(),
            ];
            yield 'unordered args 3' => fn () => [
                'modifiedAt' => Primitives::modifiedAt(),
                'filePath' => Primitives::filePath(),
                'isReadable' => Primitives::isReadOnly(),
            ];
        };
    }

    public static function fracturedMap(): Closure
    {
        return function () {
            yield 'fractured map 1' => fn () => [
                Primitives::filePath(),
                'isReadable' => Primitives::isReadOnly(),
                Primitives::modifiedAt(),
            ];
            yield 'fractured map 2' => fn () => [
                'filePath' => Primitives::filePath(),
                Primitives::modifiedAt(),
                Primitives::isReadOnly(),
            ];
            yield 'fractured map 3' => fn () => [
                'filePath' => Primitives::filePath(),
                'isReadable' => Primitives::isReadOnly(),
                Primitives::modifiedAt(),
            ];
            yield 'fractured map 4' => fn () => [
                'modifiedAt' => Primitives::modifiedAt(),
                'filePath' => Primitives::filePath(),
                Primitives::isReadOnly(),
            ];
            yield 'fractured map 5' => fn () => [
                Primitives::isReadOnly(),
                Primitives::filePath(),
                'modifiedAt' => Primitives::modifiedAt(),
            ];
            yield 'fractured map 6' => fn () => [
                Primitives::filePath(),
                Primitives::modifiedAt(),
                Primitives::isReadOnly(),
            ];
        };
    }
}
