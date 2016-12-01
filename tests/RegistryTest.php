<?php
use PHPUnit\Framework\TestCase;

/**
 * @package    Peak\Registry
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
        $list = Peak\Registry::getObjectsList();

        if(!empty($list)) {
            foreach($list as $obj_name) {
                Peak\Registry::unregister($obj_name);
            }
        }
    }
    
    function testGetInstance()
    {
        $reg = Peak\Registry::getInstance();        
        $this->assertInstanceOf('Peak\Registry', $reg); 
    }
    
    function testIsRegistered()
    {
        $result = Peak\Registry::isRegistered('unregistered_object');       
        $this->assertFalse($result);    
    }
    
    function testRegisteringObject()
    {
        $obj = Peak\Registry::set('test_obj', new RegisteredClass());       
        $this->assertInstanceOf('RegisteredClass',$obj);
        $this->assertTrue(Peak\Registry::isRegistered('test_obj'),'test_obj should be registered');    
    }
    
    function testGetObject()
    {
        $obj = Peak\Registry::get('test_obj');      
        $this->assertInstanceOf('RegisteredClass',$obj);
        
        unset($obj);        
        $obj = Peak\Registry::o()->test_obj;        
        $this->assertInstanceOf('RegisteredClass',$obj);
    }
    
    function testGetObjectsList()
    {
        $list = Peak\Registry::getObjectsList();    
        $this->assertTrue(is_array($list));                    
        $this->assertTrue(count($list) == 1);     
    }
    
    function testGetObjectClassname()
    {
        $classname = Peak\Registry::getClassName('test_obj');       
        $this->assertTrue($classname === 'RegisteredClass');
    }
    
    function testIsInstanceOf()
    {
        $this->assertTrue(Peak\Registry::isInstanceOf('test_obj', 'RegisteredClass'));      
        $this->assertFalse(Peak\Registry::isInstanceOf('test_obj', 'Unknowclass'));     
        $this->assertFalse(Peak\Registry::isInstanceOf('test_obj2', 'RegisteredClass'));
    }
    
    function testUnregisteringObject()
    {
        Peak\Registry::unregister('test_obj');  
        $this->assertFalse(Peak\Registry::isRegistered('test_obj'),'test_obj should be unregistered');
    }
  
}

// example class that will be registered by Peak\Registry
class RegisteredClass
{
    public function foo()
    {
        echo 'bar';
    }
}