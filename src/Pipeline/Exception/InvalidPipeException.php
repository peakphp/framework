<?php

declare(strict_types=1);

namespace Peak\Pipeline\Exception;

/**
 * Class InvalidPipeException
 * @package Peak\Pipeline\Exception
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
