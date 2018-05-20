<?php

use PHPUnit\Framework\TestCase;

use Peak\Common\ImmutablePropertiesBag;

class ImmutablePropertiesBagTest extends TestCase
{
    /**
     * @expectedException Exception
     */
    function testSet()
    {
        $bag = new ImmutablePropertiesBag([
            'name'    => 'Bob Ball',
            'nick'    => 'SuperBob',
        ]);

        $bag->status = 'bored';
    }

    /**
     * @expectedException Exception
     */
    function testUnset()
    {
        $bag = new ImmutablePropertiesBag([
            'name'    => 'Bob Ball',
            'nick'    => 'SuperBob',
        ]);

        unset($bag->name);
    }
}