<?php

namespace Peak\Pipelines\Exceptions;

class MissingPipeInterfaceException extends \Exception
{
    /**
     * MissingPipeInterfaceException constructor.
     *
     * @param string $name
     */
    public function __construct($name)
    {
        parent::__construct('Pipe "'.$name.'" must implements Peak\Pipelines\PipeInterface');
    }
}
