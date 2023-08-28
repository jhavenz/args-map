<?php

declare(strict_types=1);

namespace Jhavenz\MappedArguments\Tests\Dummys;

use Closure;
use DateTimeInterface;
use Illuminate\Contracts\Filesystem\Filesystem as IFilesystem;

class AmbiguousObjects
{
    use Shared;

    public static DateTimeInterface $createdAt;

    public function testMethod(DateTimeInterface $createdAt, IFilesystem $files, ?DateTimeInterface $modifiedAt = null): void
    {
        //
    }

    public static function createdAt(): int|DateTimeInterface
    {
        return self::$createdAt ??= new \DateTime();
    }

    public static function modifiedAt(): int|DateTimeInterface
    {
        return self::$modifiedAt ??= new \DateTime();
    }

    public static function unorderedKeys(): Closure
    {
        return function () {
            yield 'unordered args 1' => fn () => [
                'files' => AmbiguousObjects::files(),
                'modifiedAt' => AmbiguousObjects::modifiedAt(),
                'createdAt' => AmbiguousObjects::createdAt(),
            ];
            yield 'unordered args 2' => fn () => [
                'modifiedAt' => Objects::modifiedAt(),
                'files' => Objects::files(),
            ];
        };
    }

    public static function fracturedMap(): Closure
    {
        return function () {
            yield 'fractured map 1' => fn () => [
                AmbiguousObjects::files(),
                AmbiguousObjects::modifiedAt(),
                AmbiguousObjects::createdAt(),
            ];
            yield 'fractured map 2' => fn () => [
                AmbiguousObjects::modifiedAt(),
                AmbiguousObjects::createdAt(),
                AmbiguousObjects::files(),
            ];
            yield 'fractured map 3' => fn () => [
                AmbiguousObjects::files(),
                AmbiguousObjects::modifiedAt(),
                'createdAt' => AmbiguousObjects::createdAt(),
            ];
            yield 'fractured map 4' => fn () => [
                AmbiguousObjects::files(),
                'modifiedAt' => AmbiguousObjects::modifiedAt(),
                AmbiguousObjects::createdAt(),
            ];
        };
    }
}
