<?php

namespace Peak\Bedrock\Application\Exceptions;

class InstanceNotFoundException extends \Exception
{
    /**
     * InstanceNotFoundException constructor.
     * @param string $instance
     */
    public function __construct($instance)
    {
        $instance = filter_var($instance, FILTER_SANITIZE_STRING);
        parent::__construct('Application container does not have '.$instance);
    }
}
