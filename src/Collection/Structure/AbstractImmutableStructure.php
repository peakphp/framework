<?php

declare(strict_types=1);

namespace Peak\Collection\Structure;

use Exception;

abstract class AbstractImmutableStructure extends AbstractStructure
{
    /**
     * @param string $name
     * @param mixed $value
     * @throws Exception
     */
    public function __set(string $name, $value)
    {
        throw new Exception('Cannot modify a immutable structure');
    }
}
