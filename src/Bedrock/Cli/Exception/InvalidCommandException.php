<?php

declare(strict_types=1);

namespace Peak\Bedrock\Cli\Exception;

class InvalidCommandException extends \Exception
{
    /**
     * InvalidCommandException constructor.
     */
    public function __construct()
    {
        parent::__construct('Invalid CLI command');
    }
}
