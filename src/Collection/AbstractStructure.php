<?php

namespace Peak\Collection;

use \ArrayIterator;
use \IteratorAggregate;
use \Exception;

abstract class AbstractStructure implements IteratorAggregate
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * Define structure properties
     * @return array
     */
    abstract public function getStructure(): array;

    /**
     * AbstractStructure constructor.
     * @param null $data
     * @throws Exception
     */
    final public function __construct($data = null)
    {
        if (isset($data)) {
            if (is_array($data)) {
                $this->fromArray($data);
            } elseif (is_object($data)) {
                $this->fromObject($data);
            } else {
                throw new Exception('Structure expect an array or an object... ' . gettype($data) . ' given');
            }
        }
    }

    /**
     * @param $data
     * @return AbstractStructure
     * @throws Exception
     */
    public static function create($data)
    {
        return new static($data);
    }

    /**
     * @param string $name
     * @param $value
     * @throws Exception
     */
    public function __set(string $name, $value)
    {
        $this->set($name, $value);
    }

    /**
     * @param string $name
     * @param $value
     * @throws Exception
     */
    protected function set(string $name, $value)
    {
        $structure = $this->getStructure();
        if (!isset($structure[$name])) {
            throw new Exception('Property [' . $name . '] not defined');
        }

        if (!is_array($structure[$name])) {
            $structure[$name] = [$structure[$name]];
        }

        $valueType = strtolower(gettype($value));
        if ('object' === $valueType && !in_array($valueType, $structure[$name])) {
            $valueType = get_class($value);
        }
        if (!in_array($valueType, $structure[$name]) && !in_array('any', $structure[$name])) {
            throw new Exception('Property [' . $name . '] expect a type of (' . implode(' OR ', $structure[$name]) . ') ... ' . $valueType . ' given');
        }

        $this->data[$name] = $value;
    }

    /**
     * @param array $data
     * @return $this
     * @throws Exception
     */
    protected function fromArray(array $data)
    {
        foreach ($data as $key => $val) {
            $this->set($key, $val);
        }
        return $this;
    }

    /**
     * @param $obj
     * @return $this
     * @throws Exception
     */
    protected function fromObject($obj)
    {
        $this->fromArray(get_object_vars($obj));
        return $this;
    }

    /**
     * @param $defaultValue
     * @return $this
     */
    public function fillUndefinedWith($defaultValue)
    {
        $structure = array_keys($this->getStructure());
        foreach ($structure as $key) {
            if (!array_key_exists($key, $this->data)) {
                $this->data[$key] = $defaultValue;
            }
        }
        return $this;
    }

    /**
     * @param $name
     * @return mixed
     * @throws Exception
     */
    public function __get($name)
    {
        if (!$this->__isset($name)) {
            throw new Exception('Property [' . $name . '] not defined');
        }
        return $this->data[$name];
    }

    /**
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        return array_key_exists($name, $this->data);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }

    /**
     * @return ArrayIterator
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->data);
    }
}
