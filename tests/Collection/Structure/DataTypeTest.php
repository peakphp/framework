<?php

use \PHPUnit\Framework\TestCase;
use \Peak\Collection\Structure\DataType;

class DataTypeTest extends TestCase
{
    public function testCreate()
    {
        $dt = new DataType([], 'default');
        $this->assertTrue($dt->getDefault() === 'default');
        $dt->integer()
            ->string()
            ->float()
            ->boolean()
            ->array()
            ->object()
            ->resource();

        $this->assertTrue($dt->getTypes() === ['integer', 'string', 'double', 'boolean',  'array', 'object', 'resource']);
    }
}