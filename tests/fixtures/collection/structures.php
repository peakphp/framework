<?php

class MyStructure1 extends \Peak\Collection\Structure\AbstractStructure
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

class MyStructure2 extends \Peak\Collection\Structure\AbstractImmutableStructure
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