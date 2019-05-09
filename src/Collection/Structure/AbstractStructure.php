<?php

declare(strict_types=1);

namespace Peak\Collection\Structure;

use Peak\Blueprint\Collection\Structure;
use \ArrayIterator;
use \Exception;

use function array_key_exists;
use function get_class;
use function get_object_vars;
use function gettype;
use function implode;
use function in_array;
use function is_array;
use function is_object;
use function strtolower;

abstract class AbstractStructure implements Structure
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * AbstractStructure constructor.
     * @param mixed $data
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

        $this->fillUndefinedWithDefault();
    }

    /**
     * @param mixed $data
     * @return AbstractStructure
     * @throws Exception
     */
    public static function create($data = null)
    {
        return new static($data);
    }

    /**
     * @return array
     * @throws Exception
     */
    public static function keys(): array
    {
        return self::create()->getKeys();
    }

    /**
     * @param string $name
     * @param mixed $value
     * @throws Exception
     */
    public function __set(string $name, $value)
    {
        $this->set($name, $value);
    }

    /**
     * @param string $name
     * @param mixed $value
     * @throws Exception
     */
    protected function set(string $name, $value)
    {
        /** @var array<DataType> $structure */
        $structure = $this->getStructure();
        if (!isset($structure[$name])) {
            throw new Exception('Property [' . $name . '] not defined');
        }

        if (!$structure[$name] instanceof DataType) {
            throw new Exception('Structure definition for [' . $name . '] must be an instance of DataType');
        }

        $types = $structure[$name]->getTypes();
        $valueType = strtolower(gettype($value));

        if ('object' === $valueType && !in_array($valueType, $types)) {
            $valueType = get_class($value);
        }
        if (!in_array($valueType, $types) && !in_array('any', $types)) {
            throw new Exception('Property [' . $name . '] expect a type of (' . implode(' OR ', $types) . ') ... ' . $valueType . ' given');
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
     * @param object $obj
     * @return $this
     * @throws Exception
     */
    protected function fromObject($obj)
    {
        $this->fromArray(get_object_vars($obj));
        return $this;
    }

    /**
     * @return $this
     */
    protected function fillUndefinedWithDefault()
    {
        $structure = $this->getStructure();
        foreach ($structure as $key => $dataType) {
            if (!array_key_exists($key, $this->data)) {
                $this->data[$key] = $dataType->getDefault();
            }
        }
        return $this;
    }

    /**
     * @param string $name
     * @return mixed
     * @throws Exception
     */
    public function __get(string $name)
    {
        if (!$this->__isset($name)) {
            throw new Exception('Property [' . $name . '] not defined');
        }
        return $this->data[$name];
    }

    /**
     * @param string $name
     * @return bool
     */
    public function __isset(string $name)
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

    /**
     * @return array
     */
    public function getKeys(): array
    {
        return array_keys($this->getStructure());

    }

    /**
     * @return DataType
     */
    protected function string(): DataType
    {
        return new DataType([DataType::STRING]);
    }

    /**
     * @return DataType
     */
    protected function integer(): DataType
    {
        return new DataType([DataType::INT]);
    }

    /**
     * @return DataType
     */
    protected function float(): DataType
    {
        return new DataType([DataType::FLOAT]);
    }

    /**
     * @return DataType
     */
    protected function boolean(): DataType
    {
        return new DataType([DataType::BOOL]);
    }

    /**
     * @return DataType
     */
    protected function array(): DataType
    {
        return new DataType([DataType::ARRAY]);
    }

    /**
     * @param string $className
     * @return DataType
     */
    protected function object($className = 'object'): DataType
    {
        return new DataType([$className]);
    }

    /**
     * @return DataType
     */
    protected function resource(): DataType
    {
        return new DataType([DataType::RESOURCE]);
    }

    /**
     * @return DataType
     */
    protected function null(): DataType
    {
        return new DataType([DataType::NIL]);
    }

    /**
     * @return DataType
     */
    protected function any(): DataType
    {
        return new DataType([DataType::ANY]);
    }
}
