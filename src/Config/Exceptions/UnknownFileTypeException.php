<?php

namespace Peak\Config\Exceptions;

class UnknownFileTypeException extends \Exception
{
    /**
     * UnknownFileTypeException constructor.
     *
     * @param string $type
     */
    public function __construct(string $type)
    {
        parent::__construct('Unknown config type "'.$type.'"');
    }
}
