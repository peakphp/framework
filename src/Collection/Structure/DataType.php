<?php

namespace Peak\Collection\Structure;

class DataType
{
    const INT = 'integer';
    const BOOL = 'boolean';
    const FLOAT = 'double';
    const STRING = 'string';
    const ARRAY = 'array';
    const OBJECT = 'object';
    const RESOURCE = 'resource';
    const NIL = 'null';
    const UNKNOWN = 'unknown type';
    const ANY = 'any';

    /**
     * @var array
     */
    private $types;

    /**
     * @var mixed
     */
    private $default;

    /**
     * DataType constructor.
     * @param array $types
     * @param string $default
     */
    public function __construct(array $types, $default = null)
    {
        $this->types = $types;
        $this->default = $default;
    }

    /**
     * @return array
     */
    public function getTypes(): array
    {
        return $this->types;
    }

    /**
     * @return mixed
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @param mixed $defaultValue
     * @return $this
     */
    public function default($defaultValue)
    {
        $this->default = $defaultValue;
        return $this;
    }

    /**
     * @return $this
     */
    public function null()
    {
        $this->types[] = self::NIL;
        return $this;
    }

    /**
     * @return DataType
     */
    public function string(): DataType
    {
        $this->types[] = self::STRING;
        return $this;
    }

    /**
     * @return DataType
     */
    public function integer(): DataType
    {
        $this->types[] = self::INT;
        return $this;
    }

    /**
     * @return DataType
     */
    public function float(): DataType
    {
        $this->types[] = self::FLOAT;
        return $this;
    }

    /**
     * @return DataType
     */
    public function boolean(): DataType
    {
        $this->types[] = self::BOOL;
        return $this;
    }

    /**
     * @return DataType
     */
    public function array(): DataType
    {
        $this->types[] = self::ARRAY;
        return $this;
    }

    /**
     * @param string $className
     * @return DataType
     */
    public function object(string $className = 'object'): DataType
    {
        $this->types[] = $className;
        return $this;
    }

    /**
     * @return DataType
     */
    public function resource(): DataType
    {
        $this->types[] = self::RESOURCE;
        return $this;
    }
}
