<?php

namespace Peak\Config\Exceptions;

class FileTypesNotSupportedException extends \Exception
{
    /**
     * UnknownFileTypeException constructor.
     *
     * @param string $type
     */
    public function __construct(string $type)
    {
        parent::__construct('No support found for "'.$type.'" file type.');
    }
}
