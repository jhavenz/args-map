<?php

declare(strict_types=1);

namespace Jhavenz\MappedArguments\Tests\Dummys;

use Closure;
use Illuminate\Contracts\Filesystem\Filesystem as IFilesystem;

class Objects
{
    use Shared;

    public function testMethod(IFilesystem $files, ?\DateTimeInterface $modifiedAt = null): void
    {
        //
    }

    public static function modifiedAt(): int|\DateTimeInterface
    {
        return self::$modifiedAt ??= new \DateTime();
    }

    public static function unorderedKeys(): Closure
    {
        return function () {
            yield 'unordered args 1' => fn () => [
                'files' => Objects::files(),
                'modifiedAt' => Objects::modifiedAt(),
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
                Objects::files(),
                Objects::modifiedAt(),
            ];
            yield 'fractured map 2' => fn () => [
                Objects::modifiedAt(),
                Objects::files(),
            ];
            yield 'fractured map 3' => fn () => [
                'files' => Objects::files(),
                Objects::modifiedAt(),
            ];
            yield 'fractured map 4' => fn () => [
                Objects::files(),
                'modifiedAt' => Objects::modifiedAt(),
            ];
        };
    }
}
