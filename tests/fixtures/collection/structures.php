<?php

class MyStructure1 extends \Peak\Collection\AbstractStructure
{
    public function getStructure(): array
    {
        return [
            'id' => ['integer', 'null'],
            'date' => [\DateTime::class],
            'obj' => 'object'
        ];
    }
}

class MyStructure2 extends \Peak\Collection\AbstractImmutableStructure
{
    public function getStructure(): array
    {
        return [
            'id' => ['integer', 'null'],
            'date' => [\DateTime::class],
            'obj' => 'object'
        ];
    }
}