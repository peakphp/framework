<?php

use Peak\Collection\Structure\AbstractStructure;
use Peak\Collection\Structure\AbstractImmutableStructure;
use Peak\Collection\Structure\DataType;

class MyStructure1 extends AbstractStructure
{
    public function getStructure(): array
    {
        return [
            'id' => $this->integer()->null(),
            'date' => $this->object(\DateTime::class),
            'obj' => $this->object(),
        ];
    }
}

class MyStructure2 extends AbstractImmutableStructure
{
    public function getStructure(): array
    {
        return [
            'id' => $this->integer()->null(),
            'date' => $this->object(\DateTime::class),
            'obj' => $this->object(),
            'name' => $this->string()->default('Foo')
        ];
    }
}


class MyStructure3 extends AbstractStructure
{
    public function getStructure(): array
    {
        return [
            'multiple' => new DataType(['string', 'integer', 'null']),
        ];
    }
}

class MyStructure4 extends AbstractStructure
{
    public function getStructure(): array
    {
        return [
            'array' => $this->array(),
            'float' => $this->float(),
            'boolean' => $this->boolean(),
            'resource' => $this->resource(),
            'null' => $this->null(),
            'any' => $this->any(),
        ];
    }
}

class MyStructure5 extends AbstractStructure
{
    public function getStructure(): array
    {
        return [
            'multipleTypes1' => $this->array()->string(),
            'multipleTypes2' => $this->integer()->null()->string(),
        ];
    }
}