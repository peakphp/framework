<?php

declare(strict_types=1);

namespace Peak\Pipelines\Exception;

/**
 * Class InvalidPipeException
 * @package Peak\Pipelines\Exception
 */
class InvalidPipeException extends \Exception
{
    /**
     * InvalidPipeException constructor.
     */
    public function __construct()
    {
        parent::__construct('Invalid pipe type');
    }
}
