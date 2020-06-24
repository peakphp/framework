<?php

declare(strict_types=1);

namespace Peak\Di\Exception;

use Exception;
use Psr\Container\NotFoundExceptionInterface;

class ClassDefinitionNotFoundException extends Exception implements NotFoundExceptionInterface
{
    /**
     * ClassDefinitionNotFoundException constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        parent::__construct('Class definition for ['.$name.'] not found');
    }
}
