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
     * @param $defaultValue
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
    public function nullable()
    {
        $this->types[] = self::NIL;
        return $this;
    }
}
