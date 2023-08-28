<?php

namespace Jhavenz\MappedArguments\Tests;

use DateTimeInterface;
use Illuminate\Contracts\Filesystem\Filesystem as IFilesystem;
use Orchestra\Testbench\TestCase as Orchestra;
use Stringable;

class TestCase extends Orchestra
{
    public bool $readOnly;
    public bool $writeOnly;
    public string $filePath;
    public Stringable $filePathStringable;
    public int $modifiedAtTimestamp;
    public DateTimeInterface $modifiedAt;
    public int $createdAtTimestamp;
    public DateTimeInterface $createdAt;
    private IFilesystem $files;

    protected function setUp(): void
    {
        parent::setUp();

        // Set em all so we can assert against them
        $this->files();
        $this->isReadOnly();
        $this->filePath();
        $this->filePathStringable();
        $this->modifiedAt();
        $this->modifiedAtTimestamp();
        $this->createdAt();
        $this->createdAtTimestamp();
    }

    protected function createdAt(): \DateTime
    {
        return $this->createdAt ??= fake()->dateTimeBetween('-2 months', '-1 month');
    }

    protected function createdAtTimestamp(): int
    {
        return $this->createdAtTimestamp ??= time();
    }

    protected function modifiedAt(): \DateTime
    {
        return $this->modifiedAt ??= fake()->dateTimeBetween('-2 weeks', '-1 week');
    }

    protected function modifiedAtTimestamp(): int
    {
        return $this->modifiedAtTimestamp ??= time();
    }

    protected function files(): IFilesystem
    {
        return $this->files ??= app('filesystem.disk');
    }

    protected function filePath(): string
    {
        return $this->filePath ??= fake()->filePath();
    }

    protected function filePathStringable(): Stringable
    {
        return $this->filePathStringable ??= new class implements Stringable {
            public function __toString()
            {
                return fake()->filePath();
            }
        };
    }

    protected function isReadOnly(): bool
    {
        return $this->readOnly ??= fake()->boolean();
    }

    protected function isWriteOnly(): bool
    {
        return $this->writeOnly ??= fake()->boolean();
    }
}
