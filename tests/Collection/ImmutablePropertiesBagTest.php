<?php

use PHPUnit\Framework\TestCase;

use Peak\Collection\ImmutablePropertiesBag;

class ImmutablePropertiesBagTest extends TestCase
{
    public function testSetException()
    {
        $this->expectException(\Exception::class);
        $propertiesBag = new ImmutablePropertiesBag(['foo' => 'bar']);
        $propertiesBag->foo = 'bar2';
    }

    public function testUnsetException()
    {
        $this->expectException(\Peak\Collection\Exception\ImmutableException::class);
        $propertiesBag = new ImmutablePropertiesBag(['foo' => 'bar']);
        unset($propertiesBag->foo);
    }

    public function testOffsetSetException()
    {
        $this->expectException(\Peak\Collection\Exception\ImmutableException::class);
        $propertiesBag = new ImmutablePropertiesBag(['foo' => 'bar']);
        $propertiesBag['foo'] = 'bar2';
    }

    public function testSetSetException()
    {
        $this->expectException(\Peak\Collection\Exception\ImmutableException::class);
        $propertiesBag = new ImmutablePropertiesBag(['foo' => 'bar']);
        $propertiesBag->set('foo','bar2');
    }

    public function testOffsetUnsetException()
    {
        $this->expectException(\Peak\Collection\Exception\ImmutableException::class);
        $propertiesBag = new ImmutablePropertiesBag(['foo' => 'bar']);
        unset($propertiesBag['foo']);
    }

}
