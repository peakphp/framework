<?php

declare(strict_types=1);

namespace Peak\Di\Exception;

use Exception;
use Psr\Container\NotFoundExceptionInterface;

class ClassNotFoundException extends Exception implements NotFoundExceptionInterface
{
    /**
     * ClassNotFoundException constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        parent::__construct('Class ['.$name.'] not found');
    }
}
