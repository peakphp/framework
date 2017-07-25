<?php

namespace Peak\Di\Exception;

use Psr\Container\NotFoundExceptionInterface;
use \Exception;

class NotFoundException extends Exception implements NotFoundExceptionInterface
{
    public function __construct($name)
    {
        parent::__construct('Could not find ['.$name.'] in the container');
    }
}