<?php

declare(strict_types=1);

namespace Jhavenz\MappedArguments;

use Nette\Utils\Type;

class Param implements \Stringable
{
    private Type $type;

    private array $acceptedArguments = [];

    public function __construct(
        private readonly \ReflectionParameter $parameter,
    ) {
        $this->type = Type::fromReflection($parameter);
    }

    public function name(): string
    {
        return $this->parameter->getName();
    }

    public function value(mixed $default = null): mixed
    {
        if ($this->isMappedToItsNamedArgument()) {
            return $this->getValueByKey($this->name());
        }

        $values = array_column($this->acceptedArguments, 'value');
        $objects = array_filter($values, 'is_object');

        if (count($objects)) {
            foreach ($objects as $key => $object) {
                foreach ($this->parameterTypes() as $type) {
                    // We'll try for a direct hit on the FQCN
                    if ($object::class === $type->getName()) {
                        return $this->getValueByKey($this->acceptedArguments[$key]['key']);
                    }
                }
            }

            // Else fallback to the first object in the list
            return $objects[0];
        }

        $default ??= $this->parameter->isDefaultValueAvailable()
            ? $this->parameter->getDefaultValue()
            : null;

        return $this->acceptedArguments[0]['value'] ?? (is_callable($default) ? $default($this) : $default);
    }

    public function addArgument(string|int $key, mixed $argument): bool
    {
        if ($accepted = $this->accepts($key, $argument)) {
            $this->acceptedArguments[] = [
                'key' => $key,
                'position' => $this->parameter->getPosition(),
                'value' => is_null($argument) && $this->parameter->isDefaultValueAvailable()
                    ? $this->parameter->getDefaultValue()
                    : $argument
            ];
        }

        // Remove any 'guess' we may have accepted.
        foreach (array_column($this->acceptedArguments, 'key') as $i => $k) {
            if ($this->isMappedToItsNamedArgument() && $k !== $this->name()) {
                unset($this->acceptedArguments[$i]);
            }
        }

        return $accepted;
    }

    private function acceptsAnonymous(object $argument): bool
    {
        foreach ($this->type->getNames() as $name) {
            if (is_a($argument::class, $name, true)) {
                return true;
            }
        }

        return false;
    }

    private function isAnonymous(mixed $argument): bool
    {
        return is_object($argument) && str_contains($argument::class, '@anonymous');
    }

    private function removeNulls(): void
    {
        $this->acceptedArguments = array_filter($this->acceptedArguments, fn ($v) => !is_null($v['value']));
    }

    public function filterAccepted(array $thoseParams): void
    {
        if ($this->isMappedToItsNamedArgument()) {
            return;
        }

        $this->removeNulls();

        foreach ($this->acceptedArguments as $i => $acceptedArgument) {
            if (is_numeric($acceptedArgument['key'])
                && $acceptedArgument['position'] !== $this->parameter->getPosition()) {
                unset($this->acceptedArguments[$i]);
            }
        }

        $this->acceptedArguments = array_values($this->acceptedArguments);

        if (count($this->acceptedArguments) === 1) {
            $this->acceptedArguments = [
                [
                    'key' => $this->name(),
                    'value' => $this->acceptedArguments[0]['value'],
                    'position' => $thisPosition = $this->acceptedArguments[0]['position'],
                ]
            ];

            foreach ($thoseParams as $thatParam) {
                if ($this->name() === $thatParam->name()) {
                    continue;
                }

                foreach ($thatParam->acceptedArguments as $i => ['position' => $thatPosition]) {
                    if ($thisPosition === $thatPosition) {
                        dump(['removing' => [$thatParam->name(), $thatPosition]]);
                        unset($thatPosition->acceptedArguments[$i]);
                    }
                }
            }
        }
    }

    /** @param self[] $thoseParams */
    public function validate(array $thoseParams): void
    {
        if (!$this->parameter->isOptional() && empty($this->acceptedArguments)) {
            throw ArgumentMappingException::fromRequiredParameter($this);
        }

        foreach ($thoseParams as $thatParam) {
            if ($thatParam->name() === $this->name()) {
                continue;
            }

            if ($thatParam->sharesUnnamedArgumentWith($this)) {
                throw ArgumentMappingException::fromAmbiguousParameter($this, $thatParam);
            }
        }
    }

    private function sharesUnnamedArgumentWith(self $thatParam): bool
    {
        if (empty($this->acceptedArguments)) {
            return false;
        }

        foreach ($this->acceptedArguments as ['key' => $thisKey, 'value' => $thisValue]) {
            if (!is_numeric($thisKey)) {
                continue;
            }

            if ($thatParam->isARerun($thisKey, $thisValue)) {
                return true;
            }
        }

        return false;
    }

    private function isARerun(int|string $thatKey, mixed $thatValue): bool
    {
        return $this->contains(fn ($v, $k) => [$k, $v] === [$thatKey, $thatValue]);
    }

    public function __toString(): string
    {
        return $this->name();
    }

    public function sharedTypesWith(Param $parameter2): array
    {
        $returnValue = [];
        foreach ($this->acceptedArguments as ['key' => $key, 'value' => $thisValue]) {
            if ($parameter2->accepts($key, $thisValue)) {
                $returnValue[] = get_debug_type($thisValue);
            }
        }

        return array_unique($returnValue);
    }

    private function accepts(string|int $key, mixed $argument): bool
    {
        if ($this->isMappedToItsNamedArgument()) {
            return false;
        }

        if (!is_numeric($key) && $key !== $this->name()) {
            return false;
        }

        if (!is_numeric($key)
            && $key === $this->name()
            && is_null($argument)) {
            return $this->parameter->isDefaultValueAvailable() || throw ArgumentMappingException::fromRequiredParameter($this);
        }

        return $this->isAnonymous($argument)
            ? $this->acceptsAnonymous($argument)
            : $this->type->allows(get_debug_type($argument));
    }

    private function isMappedToItsNamedArgument(): bool
    {
        return $this->contains(fn ($_, $k) => $k === $this->name());
    }

    private function isMappedToItsPositionalArgument(): bool
    {
        return $this->contains(fn ($_, $k) => is_numeric($k) && $this->parameter->getPosition() === (int) $k);
    }

    private function contains(callable $fn): bool
    {
        foreach ($this->acceptedArguments as ['key' => $thisKey, 'value' => $thisValue]) {
            if ($fn($thisValue, $thisKey)) {
                return true;
            }
        }

        return false;
    }

    private function getValueByKey(string $name): mixed
    {
        return $this->first(fn ($_, $k) => $k === $name);
    }

    /** @return \ReflectionNamedType[] */
    private function parameterTypes(): array
    {
        $returnValue = $this->parameter->getType();

        if (method_exists($returnValue, 'getTypes')) {
            $returnValue = $returnValue->getTypes();
        }

        return is_array($returnValue) ? $returnValue : [$returnValue];
    }

    private function first(callable $fn, mixed $default = null): mixed
    {
        foreach ($this->acceptedArguments as ['key' => $key, 'value' => $value]) {
            if (true === $fn($value, $key)) {
                return $value;
            }
        }

        return $default;
    }
}
