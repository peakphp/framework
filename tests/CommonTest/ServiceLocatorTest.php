<?php
use PHPUnit\Framework\TestCase;

use Peak\Common\ServiceLocator;

class ServiceLocatorTest extends TestCase
{
    /**
     * test register / get service
     */  
    function testRegister()
    {
        $sl = new ServiceLocator();

        // via methods
        $sl->register('service1', function() {
            $obj = new \stdClass();
            $obj->name = 'hello';
            return $obj;
        });

        $service1 = $sl->getService('service1');
        $this->assertTrue($service1 instanceof \stdClass);

        // via getter / setter
        $sl->service2 = function() {
            $obj = new \stdClass();
            $obj->name = 'hello';
            return $obj;
        };

        $service2 = $sl->getService('service2');
        $this->assertTrue($service2 instanceof \stdClass);
        $this->assertTrue($sl->service2 instanceof \stdClass);
    }

    function testHas()
    {
        $sl = new ServiceLocator();
        $sl->service2 = function() {
            $obj = new \stdClass();
            $obj->name = 'hello';
            return $obj;
        };

        $this->assertTrue($sl->hasService('service2'));
        $this->assertFalse($sl->hasService('service1'));
    }

    function testListServices()
    {
        $sl = new ServiceLocator();
        $sl->service2 = function() {
            $obj = new \stdClass();
            $obj->name = 'hello';
            return $obj;
        };

        $services = $sl->listServices();
        $this->assertTrue(is_array($services));
        $this->assertTrue(count($services) == 1);
        $this->assertTrue($services[0] === 'service2');
    }

    function testException()
    {
        $sl = new ServiceLocator();
        try {
            $service = $sl->getService('unknown');
        } catch(Exception $e) { }

        $this->assertTrue(!isset($service));
    }




}