<?php

namespace Peak\Common;

use Peak\Common\Traits\UpdateToCamelCase;

use ArrayIterator;
use Countable;
use IteratorAggregate;

/**
 * Class PropertiesBag
 * @package Peak\Common
 */
class PropertiesBag implements Countable, IteratorAggregate
{
    use UpdateToCamelCase;

    /**
     * @var array
     */
    protected $properties = [];

    /**
     * Constructor.
     * @param array $properties
     */
    public function __construct(array $properties = [])
    {
        $this->properties = $this->updateToCamelCase(
            array_merge($this->properties,  $properties)
        );
    }

    /**
     * @param $property
     * @return mixed
     */
    public function __get($property)
    {
        if (!array_key_exists($property, $this->properties)) {
            trigger_error('Property '.$property.' not found', E_USER_NOTICE);
        }

        return $this->properties[$property];
    }

    /**
     * @param $key
     * @param $val
     */
    public function __set($property, $val)
    {
        $this->properties[$property] = $val;
    }

    /**
     * @param string $property
     * @return bool
     */
    public function __isset($property)
    {
        return array_key_exists($property, $this->properties);
    }

    /**
     * @param string $property
     */
    public function __unset($property)
    {
        unset($this->properties[$property]);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->properties);
    }

    /**
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->properties);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->properties;
    }
}
