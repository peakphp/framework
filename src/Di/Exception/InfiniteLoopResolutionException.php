<?php

declare(strict_types=1);

namespace Peak\Di\Exception;

use \Exception;

class InfiniteLoopResolutionException extends Exception
{
    public function __construct(string $definition)
    {
        parent::__construct('Detecting an infinite loop while resolving singleton binding '.$definition.'. This mean the container was not able to resolve a definition that has a dependency on itself too.');
    }
}
