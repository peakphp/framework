<?php

declare(strict_types=1);

namespace Peak\Collection\Structure;

use Peak\Blueprint\Collection\Structure;
use Peak\Collection\Structure\Exception\InvalidPropertyDefinitionException;
use Peak\Collection\Structure\Exception\InvalidPropertyTypeException;
use Peak\Collection\Structure\Exception\InvalidStructureException;
use Peak\Collection\Structure\Exception\UndefinedPropertyException;
use \ArrayIterator;
use \Exception;

use function array_key_exists;
use function get_class;
use function get_object_vars;
use function gettype;
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
     * @param null $data
     * @throws InvalidPropertyDefinitionException
     * @throws InvalidPropertyTypeException
     * @throws InvalidStructureException
     * @throws UndefinedPropertyException
     */
    final public function __construct($data = null)
    {
        if (isset($data)) {
            if (is_array($data)) {
                $this->fromArray($data);
            } elseif (is_object($data)) {
                $this->fromObject($data);
            } else {
                throw new InvalidStructureException(get_class($this), gettype($data));
            }
        }

        $this->fillUndefinedWithDefault();
    }

    /**
     * @param null $data
     * @return static
     * @throws InvalidPropertyDefinitionException
     * @throws InvalidPropertyTypeException
     * @throws InvalidStructureException
     * @throws UndefinedPropertyException
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
        return self::create()->getStructureKeys();
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
     * @param $value
     * @throws InvalidPropertyDefinitionException
     * @throws InvalidPropertyTypeException
     * @throws UndefinedPropertyException
     */
    protected function set(string $name, $value)
    {
        /** @var array<DataType> $structure */
        $structure = $this->getStructure();
        if (!isset($structure[$name])) {
            throw new UndefinedPropertyException($name, get_class($this));
        }

        if (!$structure[$name] instanceof DataType) {
            throw new InvalidPropertyDefinitionException($name);
        }

        $types = $structure[$name]->getTypes();
        $valueType = strtolower(gettype($value));

        if ('object' === $valueType && !in_array($valueType, $types)) {
            $valueType = get_class($value);
        }
        if (!in_array($valueType, $types) && !in_array('any', $types)) {
            throw new InvalidPropertyTypeException($name, $types, $valueType);
        }

        $this->data[$name] = $value;
    }

    /**
     * @param array $data
     * @return $this
     * @throws InvalidPropertyDefinitionException
     * @throws InvalidPropertyTypeException
     * @throws UndefinedPropertyException
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
     * @throws InvalidPropertyDefinitionException
     * @throws InvalidPropertyTypeException
     * @throws UndefinedPropertyException
     */
    protected function fromObject($obj)
    {
        $this->fromArray(get_object_vars($obj));
        return $this;
    }

    /**
     * @return $this
     * @throws InvalidPropertyDefinitionException
     */
    protected function fillUndefinedWithDefault()
    {
        $structure = $this->getStructure();
        foreach ($structure as $key => $dataType) {
            if (!array_key_exists($key, $this->data)) {
                if (!$dataType instanceof DataType) {
                    throw new InvalidPropertyDefinitionException($key);
                }
                $this->data[$key] = $dataType->getDefault();
            }
        }
        return $this;
    }

    /**
     * @param string $name
     * @return mixed
     * @throws UndefinedPropertyException
     */
    public function __get(string $name)
    {
        if (!$this->__isset($name)) {
            throw new UndefinedPropertyException($name, get_class($this));
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
    public function getStructureKeys(): array
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
