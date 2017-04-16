<?php
use PHPUnit\Framework\TestCase;

use Peak\Common\ServiceLocator;

class ServiceLocatorTest extends TestCase
{
    
    /**
     * test new instance
     */  
    function testCreateInstance()
    {
        $di = new ServiceLocator();
    }

    /**
     * test register / get service
     */  
    function testRegister()
    {
        $di = new ServiceLocator();

        // via methods
        $di->register('service1', function() {
            $obj = new \stdClass();
            $obj->name = 'hello';
            return $obj;
        });

        $service1 = $di->getService('service1');
        $this->assertTrue($service1 instanceof \stdClass);

        // via getter / setter
        
    }


}