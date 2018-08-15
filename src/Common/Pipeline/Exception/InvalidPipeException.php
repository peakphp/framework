<?php

declare(strict_types=1);

namespace Peak\Common\Pipeline\Exception;

/**
 * Class InvalidPipeException
 * @package Peak\Common\Pipeline\Exception
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
