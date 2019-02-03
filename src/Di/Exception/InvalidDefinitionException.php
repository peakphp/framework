<?php

declare(strict_types=1);

namespace Peak\Di\Exception;

use Psr\Container\ContainerExceptionInterface;
use \Exception;

class InvalidDefinitionException extends Exception implements ContainerExceptionInterface
{
    /**
     * InvalidDefinitionException constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        parent::__construct('Invalid definition for ['.$name.']');
    }
}
