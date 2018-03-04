<?php

declare(strict_types=1);

namespace Peak\Config\Exceptions;

class UnknownTypeException extends \Exception
{
    /**
     * UnknownTypeException constructor.
     */
    public function __construct()
    {
        parent::__construct('Unknown config type');
    }
}
