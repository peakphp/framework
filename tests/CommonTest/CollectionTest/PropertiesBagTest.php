<?php

use PHPUnit\Framework\TestCase;

use Peak\Common\Collection\PropertiesBag;

class PropertiesBagTest extends TestCase
{
    public function testCreate()
    {
        $propertiesBag = new PropertiesBag(['foo' => 'bar']);
        $this->assertTrue(isset($propertiesBag->foo));
        $this->assertTrue($propertiesBag->foo === 'bar');
        $this->assertTrue(count($propertiesBag) == 1);
        $this->assertTrue(count($propertiesBag->toArray()) == 1);
        unset($propertiesBag->foo);
        $this->assertFalse(isset($propertiesBag->foo));
        $this->assertTrue(count($propertiesBag) == 0);
    }
}
