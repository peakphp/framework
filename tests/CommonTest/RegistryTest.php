<?php

use PHPUnit\Framework\TestCase;

use Peak\Common\Registry;

/**
 * @package    Peak\Common\Registry
 */
class RegistryTest extends TestCase
{
    
    public static function setUpBeforeClass()
    {       
        RegistryTest::tearDownAfterClass();
    }
    
    //clean object registered inside registry
    public static function tearDownAfterClass()
    {
        $list = Registry::getObjectsList();

        if(!empty($list)) {
            foreach($list as $obj_name) {
                Registry::unregister($obj_name);
            }
        }
    }
    
    function testGetInstance()
    {
        $reg = Registry::getInstance();
        $this->assertInstanceOf(Registry::class, $reg);
    }

    function testClone()
    {
        $reg = Registry::getInstance();
        try {
            $reg2 = clone($reg);
        } catch (Exception $e) {
            $error = true;
        }

        $this->assertTrue(isset($error));
    }
    
    function testIsRegistered()
    {
        $result = Registry::isRegistered('unregistered_object');
        $this->assertFalse($result);    
    }
    
    function testRegisteringObject()
    {
        $obj = Registry::set('test_obj', new RegisteredClass());
        $this->assertInstanceOf('RegisteredClass',$obj);
        $this->assertTrue(Registry::isRegistered('test_obj'),'test_obj should be registered');
    }
    
    function testGetObject()
    {
        $obj = Registry::get('test_obj');
        $this->assertInstanceOf('RegisteredClass',$obj);
        
        unset($obj);        
        $obj = Registry::o()->test_obj;
        $this->assertInstanceOf('RegisteredClass',$obj);

        $obj = Registry::get('unknow_object');
        $this->assertTrue(is_null($obj));
    }

    function testGetAll()
    {
        $objects = Registry::getAll();
        $this->assertTrue(count($objects) == 1);
        $this->assertInstanceOf(RegisteredClass::class, $objects['test_obj']);
    }
    
    function testGetObjectsList()
    {
        $list = Registry::getObjectsList();
        $this->assertTrue(is_array($list));                    
        $this->assertTrue(count($list) == 1);     
    }
    
    function testGetObjectClassname()
    {
        $classname = Registry::getClassName('test_obj');
        $this->assertTrue($classname === 'RegisteredClass');

        $classname = Registry::getClassName('unknow');
        $this->assertFalse($classname);
    }
    
    function testIsInstanceOf()
    {
        $this->assertTrue(Registry::isInstanceOf('test_obj', 'RegisteredClass'));
        $this->assertFalse(Registry::isInstanceOf('test_obj', 'Unknowclass'));
        $this->assertFalse(Registry::isInstanceOf('test_obj2', 'RegisteredClass'));
    }
    
    function testUnregisteringObject()
    {
        Registry::unregister('test_obj');
        $this->assertFalse(Registry::isRegistered('test_obj'),'test_obj should be unregistered');
    }
  
}

// example class that will be registered by Peak\Common\Registry
class RegisteredClass
{
    public function foo()
    {
        echo 'bar';
    }
}