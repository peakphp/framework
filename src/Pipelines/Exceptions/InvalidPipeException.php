<?php

namespace Peak\Pipelines\Exceptions;

class InvalidPipeException extends \Exception
{
    /**
     * InvalidPipeException constructor.
     */
    public function __construct()
    {
        parent::__construct('Invalid pipe type');
    }
}
