<?php

declare(strict_types=1);

namespace Peak\Collection;

use Peak\Collection\Exception\ImmutableException;

/**
 * Class ImmutablePropertiesBag
 * @package Peak\Collection
 */
class ImmutablePropertiesBag extends PropertiesBag
{
    /**
     * Disable overriding of __set and throw exception if used
     * @throws ImmutableException
     */
    final public function __set(string $property, $val)
    {
        throw new ImmutableException('modified');
    }

    /**
     * Disable overriding of __unset and throw exception if used
     * @throws ImmutableException
     */
    final public function __unset(string $property)
    {
        throw new ImmutableException('unset');
    }

    /**
     * Assign a value to the specified offset
     * @throws ImmutableException
     */
    final public function offsetSet($offset, $value)
    {
        throw new ImmutableException('modified');
    }

    /**
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
