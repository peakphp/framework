<?php

declare(strict_types=1);

namespace Peak\Di\Exception;

use Psr\Container\NotFoundExceptionInterface;
use \Exception;

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
