<?php

declare(strict_types=1);

namespace Peak\Di\Exception;

use \Exception;

class NoClassDefinitionException extends Exception
{
    /**
     * NoClassDefinitionException constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        parent::__construct('no definition found for '.$name);
    }
}
