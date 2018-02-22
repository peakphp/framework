<?php

namespace Peak\Bedrock\View\Exceptions;

class EngineNotSetException extends \Exception
{
    /**
     * EngineNotSetException constructor.
     */
    public function __construct()
    {
        parent::__construct('View rendering engine not set');
    }
}
