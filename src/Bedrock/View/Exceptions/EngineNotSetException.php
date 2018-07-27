<?php

declare(strict_types=1);

namespace Peak\Bedrock\View\Exceptions;

/**
 * Class EngineNotSetException
 * @package Peak\Bedrock\View\Exceptions
 */
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
