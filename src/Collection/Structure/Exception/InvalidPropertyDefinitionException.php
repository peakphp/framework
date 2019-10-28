<?php

declare(strict_types=1);

namespace Peak\Collection\Structure\Exception;

class InvalidPropertyDefinitionException extends \Exception
{
    /**
     * InvalidPropertyDefinitionException constructor.
     * @param string $propertyName
     */
    public function __construct(string $propertyName)
    {
        parent::__construct('Structure definition for [' . $propertyName . '] must be an instance of DataType');
    }
}
