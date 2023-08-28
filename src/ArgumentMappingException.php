<?php

declare(strict_types=1);

namespace Jhavenz\MappedArguments;

use LogicException;

class ArgumentMappingException extends LogicException
{
    public static function fromAmbiguousParameter(Param $parameter1, Param $parameter2): static
    {
        $sharedTypes = implode(', ', $parameter1->sharedTypesWith($parameter2));

        return new static("Ambiguous unnamed arguments found for the [{$parameter1}] and [{$parameter2}] parameters. They both share [{$sharedTypes}] type signatures");
    }

    public static function fromRequiredParameter(Param $parameter): static
    {
        return new static("Missing required argument for the [{$parameter}] parameter");
    }
}
