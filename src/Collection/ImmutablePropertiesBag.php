<?php

declare(strict_types=1);

namespace Peak\Collection;

use \Exception;

/**
 * Class ImmutablePropertiesBag
 * @package Peak\Collection
 */
class ImmutablePropertiesBag extends PropertiesBag
{
    /**
     * Disable overriding of __set and throw exception if used
     * @throws Exception
     */
    final public function __set($property, $val)
    {
        throw new Exception(__CLASS__.': properties of immutable object cannot be modified');
    }

    /**
     * Disable overriding of __unset and throw exception if used
     * @throws Exception
     */
    final public function __unset($property)
    {
        throw new Exception(__CLASS__.': properties of immutable object cannot be unset');
    }

    /**
     * Assign a value to the specified offset
     * @throws Exception
     */
    final public function offsetSet($offset, $value)
    {
        throw new Exception(__CLASS__.': properties of immutable object cannot be modified');
    }

    /**
     * Item to delete
     * @throws Exception
     */
    final public function offsetUnset($offset)
    {
        throw new Exception(__CLASS__.': properties of immutable object cannot be unset');
    }
}
