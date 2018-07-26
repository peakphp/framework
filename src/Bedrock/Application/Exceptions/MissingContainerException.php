<?php

declare(strict_types=1);

namespace Peak\Bedrock\Application\Exceptions;

/**
 * Class MissingContainerException
 * @package Peak\Bedrock\Application\Exceptions
 */
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
