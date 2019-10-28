<?php

declare(strict_types=1);

namespace Peak\Collection\Structure\Exception;

class UndefinedPropertyException extends \Exception
{
    /**
     * UndefinedPropertyException constructor.
     * @param string $propertyName
     * @param string $class
     */
    public function __construct(string $propertyName, string $class)
    {
        parent::__construct('Property [' . $propertyName . '] is undefined in the structure of '.$class);
    }
}
