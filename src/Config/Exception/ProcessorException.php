<?php

declare(strict_types=1);

namespace Peak\Config\Exception;

/**
 * Class ProcessorException
 * @package Peak\Config\Exception
 */
class ProcessorException extends \Exception
{
    /**
     * ProcessorException constructor.
     *
     * @param string $msg
     */
    public function __construct(string $msg)
    {
        parent::__construct($msg);
    }
}
