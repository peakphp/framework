<?php

use PHPUnit\Framework\TestCase;

use Peak\Collection\PropertiesBag;

class PropertiesBagTest extends TestCase
{
    public function testCreate()
    {
        $propertiesBag = new PropertiesBag(['foo' => 'bar']);
        $this->assertTrue(isset($propertiesBag->foo));
        $this->assertTrue(isset($propertiesBag['foo']));
        $this->assertTrue($propertiesBag->foo === 'bar');
        $this->assertTrue(count($propertiesBag) == 1);
        $this->assertTrue(count($propertiesBag->toArray()) == 1);
        $propertiesBag->foo = 'bar2';
        $this->assertTrue($propertiesBag->foo === 'bar2');
        unset($propertiesBag->foo);

        $this->assertFalse(isset($propertiesBag->foo));
        $this->assertTrue(count($propertiesBag) == 0);
        $propertiesBag['foo'] = 'bar';
        $propertiesBag['bar'] = 'foo';
        $this->assertTrue($propertiesBag['foo'] === 'bar');
        $this->assertTrue($propertiesBag['bar'] === 'foo');
        unset($propertiesBag['foo']);
        $this->assertFalse(isset($propertiesBag['foo']));
    }

    /**
     * @expectedException \Exception
     */
    public function testException()
    {
        $propertiesBag = new PropertiesBag(['foo' => 'bar']);
        $propertiesBag->test;
    }

    public function testIterator()
    {
        $propertiesBag = new PropertiesBag(['foo' => 'bar']);
        foreach ($propertiesBag as $key => $val) {
            $this->assertTrue($key === 'foo');
            $this->assertTrue($val === 'bar');
        }
    }

    public function testSerialize()
    {
        $propertiesBag = new PropertiesBag(['foo' => 'bar']);
        $serialized = serialize($propertiesBag);

        $unserialized = unserialize($serialized);
        $this->assertInstanceOf(PropertiesBag::class, $unserialized);
    }
}
