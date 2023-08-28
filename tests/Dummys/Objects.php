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
            yield fn () => [
                'files' => Objects::files(),
                'modifiedAt' => Objects::modifiedAt(),
            ];
            yield fn () => [
                'modifiedAt' => Objects::modifiedAt(),
                'files' => Objects::files(),
            ];
            yield fn () => [
                'modifiedAt' => Objects::modifiedAt(),
                'files' => Objects::files(),
            ];
        };
    }

    public static function fracturedMap(): Closure
    {
        return function () {
            yield fn () => [
                Objects::files(),
                Objects::modifiedAt(),
            ];
            yield fn () => [
                Objects::modifiedAt(),
                Objects::files(),
            ];
            yield fn () => [
                'files' => Objects::files(),
                Objects::modifiedAt(),
            ];
            yield fn () => [
                Objects::files(),
                'modifiedAt' => Objects::modifiedAt(),
            ];
        };
    }
}
