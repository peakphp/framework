<?php

namespace Peak\Config\Exceptions;

class NoFileHandlersException extends \Exception
{
    /**
     * NoFileHandlersException constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        parent::__construct('No support found for "'.$name.'" file type.');
    }
}
