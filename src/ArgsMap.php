<?php

namespace Jhavenz\MappedArguments;

use Nette\Utils\Callback;
use ReflectionMethod;
use ReflectionParameter;

class ArgsMap
{
    private array $mappedResults = [];

    /** @var Param[] $params */
    private array $params;

    private array $defaults = [];

    protected function __construct(
        private array $arguments,
        ReflectionParameter ...$parameters,
    ) {
        $this->params = array_map(function ($value) {
            return new Param($value);
        }, $parameters);
    }

    public static function fromCallable(ReflectionMethod|callable|string $method, array $arguments = []): static
    {
        $params = $method instanceof \ReflectionMethod ? $method->getParameters() : Callback::toReflection($method)->getParameters();

        return new static($arguments, ...$params);
    }

    public function create(): array
    {
        if (count($this->mappedResults)) {
            return $this->mappedResults;
        }

        foreach ($this->params as $param) {
            foreach ($this->arguments as $key => $argument) {
                $param->addArgument($key, $argument);
            }
        }

        foreach ($this->params as $param) {
            $param->validate($this->params);

            $default = $this->defaults[$param->name()] ?? null;

            $this->mappedResults[$param->name()] = $param->value($default);
        }

        return $this->mappedResults;
    }

    public function withArgument(string|int $key, mixed $value): static
    {
        $this->arguments[$key] = $value;

        return $this;
    }

    public function setDefaults(array $defaults): static
    {
        $this->defaults = $defaults;

        return $this;
    }
}
