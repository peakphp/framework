<?php

declare(strict_types=1);

namespace Peak\Config\Exception;

use Peak\Blueprint\Config\ConfigException;

class LoaderException extends \Exception implements ConfigException
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
