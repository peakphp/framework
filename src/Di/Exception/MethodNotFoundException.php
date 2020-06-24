<?php

declare(strict_types=1);

namespace Peak\Di\Exception;

use Exception;
use Psr\Container\NotFoundExceptionInterface;

class MethodNotFoundException extends Exception implements NotFoundExceptionInterface
{
    /**
     * MethodNotFoundException constructor.
     * @param string $class
     * @param string $method
     */
    public function __construct(string $class, string $method)
    {
        parent::__construct('Method ['.$method.'] not found in class ['.$class.']');
    }
}
