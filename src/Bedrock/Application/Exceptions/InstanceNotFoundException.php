<?php

declare(strict_types=1);

namespace Peak\Bedrock\Application\Exceptions;

/**
 * Class InstanceNotFoundException
 * @package Peak\Bedrock\Application\Exceptions
 */
class InstanceNotFoundException extends \Exception
{
    /**
     * InstanceNotFoundException constructor.
     *
     * @param string $instance
     */
    public function __construct(string $instance)
    {
        $instance = filter_var($instance, FILTER_SANITIZE_STRING);
        parent::__construct('Application container does not have '.$instance);
    }
}
