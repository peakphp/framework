<?php

declare(strict_types=1);

namespace Peak\Collection\Exception;

class ImmutableException extends \Exception
{
    public function __construct(string $action)
    {
        parent::__construct('properties of immutable object cannot be '.$action);
    }
}
