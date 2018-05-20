<?php

namespace Peak\Common;

use \Exception;

/**
 * Class ImmutablePropertiesBag
 * @package Peak\Common
 */
class ImmutablePropertiesBag extends PropertiesBag
{
    /**
     * Disable overriding of __set and throw exception if used
     * @throws Exception
     */
    final public function __set($property, $val)
    {
        throw new Exception(get_class($this).': properties of immutable object cannot be modified');
    }

    /**
     * Disable overriding of __unset and throw exception if used
     * @throws Exception
     */
    final public function __unset($property)
    {
        throw new Exception(get_class($this).': properties of immutable object cannot be unset');
    }
}
