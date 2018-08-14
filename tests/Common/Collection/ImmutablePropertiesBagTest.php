<?php

use PHPUnit\Framework\TestCase;

use Peak\Common\Collection\ImmutablePropertiesBag;

class ImmutablePropertiesBagTest extends TestCase
{
    /**
     * @expectedException \Exception
     */
    public function testSetException()
    {
        $propertiesBag = new ImmutablePropertiesBag(['foo' => 'bar']);
        $propertiesBag->foo = 'bar2';
    }

    /**
     * @expectedException \Exception
     */
    public function testUnsetException()
    {
        $propertiesBag = new ImmutablePropertiesBag(['foo' => 'bar']);
        unset($propertiesBag->foo);
    }

    /**
     * @expectedException \Exception
     */
    public function testOffsetSetException()
    {
        $propertiesBag = new ImmutablePropertiesBag(['foo' => 'bar']);
        $propertiesBag['foo'] = 'bar2';
    }

    /**
     * @expectedException \Exception
     */
    public function testOffsetUnsetException()
    {
        $propertiesBag = new ImmutablePropertiesBag(['foo' => 'bar']);
        unset($propertiesBag['foo']);
    }

}
