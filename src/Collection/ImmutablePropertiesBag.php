<?php

declare(strict_types=1);

namespace Peak\Collection;

use Peak\Collection\Exception\ImmutableException;

class ImmutablePropertiesBag extends PropertiesBag
{
    /**
     * Disable overriding of __set and throw exception if used
     * @param string $property
     * @param mixed $val
     * @throws ImmutableException
     */
    final public function __set(string $property, $val)
    {
        throw new ImmutableException('modified');
    }

    /**
     * Disable overriding of __unset and throw exception if used
     * @param string $property
     * @throws ImmutableException
     */
    final public function __unset(string $property)
    {
        throw new ImmutableException('unset');
    }

    /**
     * @param string $offset
     * @param mixed $value
     * @throws ImmutableException
     */
    final public function offsetSet($offset, $value)
    {
        throw new ImmutableException('modified');
    }

    /**
     * @param string $offset
     * @throws ImmutableException
     */
    final public function offsetUnset($offset)
    {
        throw new ImmutableException('unset');
    }

    /**
     * @param string $property
     * @param $value
     * @throws ImmutableException
     */
    final public function set(string $property, $value)
    {
        throw new ImmutableException('modified');
    }
}
