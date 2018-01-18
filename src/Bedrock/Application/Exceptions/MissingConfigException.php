<?php

namespace Peak\Bedrock\Application\Exceptions;

class MissingConfigException extends \Exception
{
    /**
     * MissingConfigException constructor.
     * @param $config_name
     */
    public function __construct($config_name)
    {
        parent::__construct('Configuration "'.$config_name.'" is missing');
    }
}
