<?php

use Peak\Collection\Structure\AbstractStructure;
use Peak\Collection\Structure\AbstractImmutableStructure;
use Peak\Collection\Structure\DataType;

class MyStructure1 extends AbstractStructure
{
    public function getStructure(): array
    {
        return [
            'id' => $this->integer()->nullable(),
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
            'id' => $this->integer()->nullable(),
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