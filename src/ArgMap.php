<?php

namespace Jhavenz\MappedArguments;

use Illuminate\Support\Arr;
use Nette\Utils\Callback;
use Nette\Utils\Type;
use ReflectionMethod;
use ReflectionParameter;

class ArgMap
{
    public static function create(ReflectionMethod|callable|string $method, array $arguments): array
    {
        $params = Arr::keyBy(self::params($method), fn ($param) => $param->getName());

        $returnValues = [];
        foreach ($params as $parameter) {
            $types = Type::fromReflection($parameter)->getTypes();

            foreach ($types as $type) {
                foreach ($arguments as $key => $argument) {
                    if (self::isAnonymousClass($argument)) {
                        foreach ($type->getNames() as $name) {
                            if (is_a($argument::class, $name, true)) {
                                $returnValues[$parameter->getName()] = $argument;
                                unset($arguments[$key]);
                                continue 4;
                            }
                        }

                        continue;
                    }

                    if ($type->allows(get_debug_type($argument))) {
                        $returnValues[$parameter->getName()] = $argument;
                        unset($arguments[$key]);
                        continue 3;
                    }
                }

                if (!$parameter->isOptional() && !isset($returnValues[$parameter->getName()])) {
                    throw new \InvalidArgumentException("Missing required argument for parameter {$parameter->getName()}.");
                }
            }
        }

        return collect($returnValues)->sortBy(array_keys($params))->all();
    }

    /** @return ReflectionParameter[] */
    private static function params(callable|ReflectionMethod|string $method): array
    {
        return $method instanceof \ReflectionMethod ? $method->getParameters() : Callback::toReflection($method)->getParameters();
    }

    private static function isAnonymousClass(mixed $argument): bool
    {
        return is_object($argument) && str_contains($argument::class, '@anonymous');
    }
}
