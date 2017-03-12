<?php
use PHPUnit\Framework\TestCase;

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
        $list = Peak\Common\Registry::getObjectsList();

        if(!empty($list)) {
            foreach($list as $obj_name) {
                Peak\Common\Registry::unregister($obj_name);
            }
        }
    }
    
    function testGetInstance()
    {
        $reg = Peak\Common\Registry::getInstance();        
        $this->assertInstanceOf('Peak\Common\Registry', $reg); 
    }
    
    function testIsRegistered()
    {
        $result = Peak\Common\Registry::isRegistered('unregistered_object');       
        $this->assertFalse($result);    
    }
    
    function testRegisteringObject()
    {
        $obj = Peak\Common\Registry::set('test_obj', new RegisteredClass());       
        $this->assertInstanceOf('RegisteredClass',$obj);
        $this->assertTrue(Peak\Common\Registry::isRegistered('test_obj'),'test_obj should be registered');    
    }
    
    function testGetObject()
    {
        $obj = Peak\Common\Registry::get('test_obj');      
        $this->assertInstanceOf('RegisteredClass',$obj);
        
        unset($obj);        
        $obj = Peak\Common\Registry::o()->test_obj;        
        $this->assertInstanceOf('RegisteredClass',$obj);
    }
    
    function testGetObjectsList()
    {
        $list = Peak\Common\Registry::getObjectsList();    
        $this->assertTrue(is_array($list));                    
        $this->assertTrue(count($list) == 1);     
    }
    
    function testGetObjectClassname()
    {
        $classname = Peak\Common\Registry::getClassName('test_obj');       
        $this->assertTrue($classname === 'RegisteredClass');
    }
    
    function testIsInstanceOf()
    {
        $this->assertTrue(Peak\Common\Registry::isInstanceOf('test_obj', 'RegisteredClass'));      
        $this->assertFalse(Peak\Common\Registry::isInstanceOf('test_obj', 'Unknowclass'));     
        $this->assertFalse(Peak\Common\Registry::isInstanceOf('test_obj2', 'RegisteredClass'));
    }
    
    function testUnregisteringObject()
    {
        Peak\Common\Registry::unregister('test_obj');  
        $this->assertFalse(Peak\Common\Registry::isRegistered('test_obj'),'test_obj should be unregistered');
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