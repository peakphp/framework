<?php

declare(strict_types=1);

namespace Peak\Collection\Structure\Exception;

class InvalidStructureException extends \Exception
{
    /**
     * UndefinedPropertyException constructor.
     * @param string $class
     * @param string $typeReceived
     */
    public function __construct(string $class, string $typeReceived)
    {
        parent::__construct('Structure '.$class.' expect an array or an object... ' . $typeReceived . ' given');
    }
}
