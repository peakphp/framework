<?php

namespace Peak\Bedrock\Application\Exceptions;

class MissingContainerException extends \Exception
{
    /**
     * MissingContainerException constructor.
     */
    public function __construct()
    {
        parent::__construct('Application has no container');
    }
}
