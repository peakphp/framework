<?php

use PHPUnit\Framework\TestCase;

use Peak\Common\PropertiesBag;

class PropertiesBagTest extends TestCase
{
    /**
     * test __get() && __isset()
     */
    function testGet()
    {
        $bag = new PropertiesBag([
            'name'    => 'Bob Ball',
            'nick'    => 'SuperBob',
            'age'     => '75',
            'passions' => [
                'petanque',
                'bowling',
                'curling',
            ],
            'active'  => 0,
        ]);

        //offset get exists
        $this->assertTrue($bag->age == 75);
        $this->assertTrue(isset($bag->passions));
        $this->assertFalse(isset($bag->sport));
        $this->assertTrue(isset($bag->active));
        $this->assertTrue(isset($bag->passions[0]));
        $this->assertFalse(isset($bag->passions[9]));
        $this->assertFalse(isset($bag->status));
    }

    /**
     * test __set()
     */
    function testSetter()
    {
        $bob = new PropertiesBag();
        $bob->age = 35;
        $bob->sport = 'basketball';

        $this->assertTrue($bob->age == 35);
        $this->assertTrue(isset($bob->sport));
        $this->assertFalse(isset($bob->status));

        $bob->age = 36;
        $this->assertTrue($bob->age == 36);
    }

    /**
     * test __unset()
     */
    function testUnset()
    {
        $bob = new PropertiesBag();
        $bob->age = 35;
        $bob->sport = 'basketball';

        $this->assertTrue($bob->age == 35);
        $this->assertTrue(isset($bob->sport));
        $this->assertFalse(isset($bob->status));

        unset($bob->age);
        $this->assertFalse(isset($bob->age));
    }

    /**
     * test count()
     */
    function testCount()
    {
        $bob = new PropertiesBag();
        $bob->age = 35;
        $bob->sport = 'basketball';

        $this->assertTrue(count($bob) == 2);
        unset($bob->age);
        $this->assertTrue(count($bob) == 1);
    }

    /**
     * test getIterator()
     */
    function testIterator()
    {
        $bob = new PropertiesBag();
        $bob->age = 35;
        $bob->sport = 'basketball';

        $this->assertInstanceOf('Traversable', $bob);

        $iterator = $bob->getIterator();
        $this->assertInstanceOf('ArrayIterator', $iterator);
    }

    /**
     * test toArray()
     */
    function testToArray()
    {
        $bob = new PropertiesBag([
            'status' => 'bored',
            'age' => 25,
        ]);
        $bob->age = 35;
        $bob->sport = 'basketball';

        $array = $bob->toArray();
        $this->assertTrue(is_array($array));
        $this->assertTrue(count($array) == 3);
    }
}