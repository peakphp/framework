<?php

namespace Peak\Config\Exceptions;

class InvalidFileHandlerException extends \Exception
{
    /**
     * InvalidTypeDefinitionException constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        parent::__construct('Invalid file handler definition for "'.$name.'"');
    }
}
