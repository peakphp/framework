<?php

namespace Peak\Config\Exceptions;

class InvalidTypeDefinitionException extends \Exception
{
    /**
     * InvalidTypeDefinitionException constructor.
     *
     * @param string $type
     */
    public function __construct(string $type)
    {
        parent::__construct('Invalid file type definition for "'.$type.'"');
    }
}
