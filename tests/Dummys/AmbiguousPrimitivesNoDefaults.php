<?php

declare(strict_types=1);

namespace Jhavenz\MappedArguments\Tests\Dummys;

use Closure;

class AmbiguousPrimitivesNoDefaults
{
    use Shared;

    private static bool $isWriteOnly;

    public function testMethod(int $modifiedAt, bool $isReadOnly, ?bool $isWriteOnly): void
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
                'filePath' => AmbiguousPrimitivesNoDefaults::filePath(),
                'isReadOnly' => AmbiguousPrimitivesNoDefaults::isReadOnly(),
                'isWriteOnly' => AmbiguousPrimitivesNoDefaults::isWriteOnly(),
            ];
            yield 'unordered args 2' => fn () => [
                'isReadOnly' => AmbiguousPrimitivesNoDefaults::isReadOnly(),
                'isWriteOnly' => AmbiguousPrimitivesNoDefaults::isWriteOnly(),
                'filePath' => AmbiguousPrimitivesNoDefaults::filePath(),
            ];
            yield 'unordered args 3' => fn () => [
                'isWriteOnly' => AmbiguousPrimitivesNoDefaults::isWriteOnly(),
                'filePath' => AmbiguousPrimitivesNoDefaults::filePath(),
                'isReadOnly' => AmbiguousPrimitivesNoDefaults::isReadOnly(),
            ];
        };
    }

    public static function fracturedMap(): Closure
    {
        return function () {
            yield 'fractured map 1' => fn () => [
                AmbiguousPrimitivesNoDefaults::filePath(),
                'isReadOnly' => AmbiguousPrimitivesNoDefaults::isReadOnly(),
                AmbiguousPrimitivesNoDefaults::isWriteOnly(),
            ];
            yield 'fractured map 2' => fn () => [
                'filePath' => AmbiguousPrimitivesNoDefaults::filePath(),
                AmbiguousPrimitivesNoDefaults::isWriteOnly(),
                AmbiguousPrimitivesNoDefaults::isReadOnly(),
            ];
            yield 'fractured map 3' => fn () => [
                'filePath' => AmbiguousPrimitivesNoDefaults::filePath(),
                'isReadOnly' => AmbiguousPrimitivesNoDefaults::isReadOnly(),
                AmbiguousPrimitivesNoDefaults::isWriteOnly(),
            ];
            yield 'fractured map 4' => fn () => [
                'isWriteOnly' => AmbiguousPrimitivesNoDefaults::isWriteOnly(),
                'filePath' => AmbiguousPrimitivesNoDefaults::filePath(),
                AmbiguousPrimitivesNoDefaults::isReadOnly(),
            ];
            yield 'fractured map 5' => fn () => [
                AmbiguousPrimitivesNoDefaults::isReadOnly(),
                AmbiguousPrimitivesNoDefaults::filePath(),
                'isWriteOnly' => AmbiguousPrimitivesNoDefaults::isWriteOnly(),
            ];
            yield 'fractured map 6' => fn () => [
                AmbiguousPrimitivesNoDefaults::filePath(),
                AmbiguousPrimitivesNoDefaults::isWriteOnly(),
                AmbiguousPrimitivesNoDefaults::isReadOnly(),
            ];
        };
    }
}
