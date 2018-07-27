<?php

declare(strict_types=1);

namespace Peak\Bedrock\Application\Exceptions;

/**
 * Class MissingConfigException
 * @package Peak\Bedrock\Application\Exceptions
 */
class MissingConfigException extends \Exception
{
    /**
     * MissingConfigException constructor.
     *
     * @param string $config_name
     */
    public function __construct(string $config_name)
    {
        parent::__construct('Configuration "'.$config_name.'" is missing');
    }
}
