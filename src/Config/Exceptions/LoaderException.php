<?php

namespace Peak\Config\Exceptions;

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
