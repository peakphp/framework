<?php

namespace Peak\Bedrock\Application\Exceptions;

class ControllerNotFoundException extends \Exception
{
    /**
     * ControllerNotFoundException constructor.
     * @param string $controller_name
     */
    public function __construct($controller_name)
    {
        parent::__construct('Application controller '.trim(strip_tags($controller_name)).' not found');
    }
}
