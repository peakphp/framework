<?php

declare(strict_types=1);

namespace Peak\Config\Exception;

/**
 * Class LoaderException
 * @package Peak\Config\Exception
 */
class LoaderException extends \Exception
{
    /**
     * LoaderException constructor.
     *
     * @param string $msg
     */
    public function __construct(string $msg)
    {
        parent::__construct($msg);
    }
}
