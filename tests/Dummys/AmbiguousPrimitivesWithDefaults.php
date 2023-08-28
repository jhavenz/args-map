<?php

declare(strict_types=1);

namespace Jhavenz\MappedArguments\Tests\Dummys;

use Closure;

class AmbiguousPrimitivesWithDefaults
{
    use Shared;

    private static bool $isWriteOnly;

    public function testMethod(int $modifiedAt, bool $isReadOnly = false, ?bool $isWriteOnly = null): void
    {
        //
    }

    public static function isWriteOnly(): bool
    {
        return self::$isWriteOnly ??= true;
    }

    public static function unorderedKeys(): Closure
    {
        return function () {
            yield 'unordered args 1' => fn () => [
                'filePath' => AmbiguousPrimitivesWithDefaults::filePath(),
                'isReadOnly' => AmbiguousPrimitivesWithDefaults::isReadOnly(),
                'isWriteOnly' => AmbiguousPrimitivesWithDefaults::isWriteOnly(),
            ];
            yield 'unordered args 2' => fn () => [
                'isReadOnly' => AmbiguousPrimitivesWithDefaults::isReadOnly(),
                'isWriteOnly' => AmbiguousPrimitivesWithDefaults::isWriteOnly(),
                'filePath' => AmbiguousPrimitivesWithDefaults::filePath(),
            ];
            yield 'unordered args 3' => fn () => [
                'isWriteOnly' => AmbiguousPrimitivesWithDefaults::isWriteOnly(),
                'filePath' => AmbiguousPrimitivesWithDefaults::filePath(),
                'isReadOnly' => AmbiguousPrimitivesWithDefaults::isReadOnly(),
            ];
        };
    }

    public static function fracturedMap(): Closure
    {
        return function () {
            yield 'fractured map 1' => fn () => [
                AmbiguousPrimitivesWithDefaults::filePath(),
                'isReadOnly' => AmbiguousPrimitivesWithDefaults::isReadOnly(),
                AmbiguousPrimitivesWithDefaults::isWriteOnly(),
            ];
            yield 'fractured map 2' => fn () => [
                'filePath' => AmbiguousPrimitivesWithDefaults::filePath(),
                AmbiguousPrimitivesWithDefaults::isWriteOnly(),
                AmbiguousPrimitivesWithDefaults::isReadOnly(),
            ];
            yield 'fractured map 3' => fn () => [
                'filePath' => AmbiguousPrimitivesWithDefaults::filePath(),
                'isReadOnly' => AmbiguousPrimitivesWithDefaults::isReadOnly(),
                AmbiguousPrimitivesWithDefaults::isWriteOnly(),
            ];
            yield 'fractured map 4' => fn () => [
                'isWriteOnly' => AmbiguousPrimitivesWithDefaults::isWriteOnly(),
                'filePath' => AmbiguousPrimitivesWithDefaults::filePath(),
                AmbiguousPrimitivesWithDefaults::isReadOnly(),
            ];
            yield 'fractured map 5' => fn () => [
                AmbiguousPrimitivesWithDefaults::isReadOnly(),
                AmbiguousPrimitivesWithDefaults::filePath(),
                'isWriteOnly' => AmbiguousPrimitivesWithDefaults::isWriteOnly(),
            ];
            yield 'fractured map 6' => fn () => [
                AmbiguousPrimitivesWithDefaults::filePath(),
                AmbiguousPrimitivesWithDefaults::isWriteOnly(),
                AmbiguousPrimitivesWithDefaults::isReadOnly(),
            ];
        };
    }
}
