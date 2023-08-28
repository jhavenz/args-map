<?php

declare(strict_types=1);

namespace Jhavenz\MappedArguments\Tests\Dummys;

use Illuminate\Contracts\Filesystem\Filesystem;
use ReflectionMethod;

trait Shared
{
    public static bool $readonly;
    public static \Stringable|string $filePath;
    public static \DateTimeInterface|int $modifiedAt;

    public static function files(): Filesystem
    {
        return app('filesystem.disk');
    }

    public static function filePath(): string|\Stringable
    {
        return static::$filePath ??= fake()->filePath();
    }

    public static function modifiedAt(): int|\DateTimeInterface
    {
        return static::$modifiedAt ??= time();
    }

    public static function isReadOnly(): bool
    {
        return static::$readonly ??= true;
    }

    public static function reflect(): ReflectionMethod
    {
        return new ReflectionMethod(static::class, 'testMethod');
    }
}
