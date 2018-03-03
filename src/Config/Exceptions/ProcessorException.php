<?php

namespace Peak\Config\Exceptions;

class ProcessorException extends \Exception
{
    /**
     * ProcessorException constructor.
     *
     * @param string $msg
     */
    public function __construct(string $msg)
    {
        parent::__construct($msg);
    }
}
