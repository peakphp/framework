<?php
use PHPUnit\Framework\TestCase;

/**
 * @package    Peak\Config
 */
class ConfigTest extends TestCase
{
	
	/**
	 * instanciate class for tests
	 */
	function setUp()
	{		
		$this->peakconfig = new Peak\Config();
	}
		 
	/**
	 * test new instance
	 */  
	function testCreateInstance()
	{
		$cf = new Peak\Config();		
		$this->assertInstanceOf('Peak\Config', $cf);
		$this->assertObjectHasAttribute('_vars', $cf);
	}
	
	/**
	 * test object isset() and unset
	 */
	function testSetIssetUnset()
	{
		$this->peakconfig->myvar = 'value';		
		$this->assertTrue(isset($this->peakconfig->myvar));
		
		unset($this->peakconfig->myvar);		
		$this->assertFalse(isset($this->peakconfig->myvar));		
	}
	
	/**
	 * test object count()
	 */
	function testCount()
	{
		$this->assertTrue(count($this->peakconfig) == 0);		
		$this->peakconfig->myvar = 'value';
		$this->assertTrue(count($this->peakconfig) == 1);	
	}
	
	/**
	 * test method setVars()
	 */
	function testSetVars()
	{
		$array = array('myvar' => 'value', 'test', 'test2' => 'value2');
		$this->peakconfig->setVars($array);
		$this->assertTrue(isset($this->peakconfig->myvar));
	}
	
	/**
	 * test method getVars()
	 */
	function testGetVars()
	{
		$array = array('myvar' => 'value', 'test', 'test2' => 'value2');
		$this->peakconfig->setVars($array);
		
		$vars = $this->peakconfig->getVars();
		$this->assertTrue($vars['myvar'] === 'value');
	}
	
	/**
	 * test class iterator
	 */
	function testIterator()
	{
		$this->peakconfig->setVars(array('myvar' => 'value', 'test', 'test2' => 'value2'));
		
		$count = 0;
		foreach($this->peakconfig as $key) ++$count;
		
		$this->assertTrue(count($this->peakconfig) == 3);
	}
	
	/**
	 * test method loadFile()
	 */
	function testLoadFile()
	{
		$cf = new Peak\Config();		
		$cf->loadFile(dirname(__FILE__).'/ConfigTest/appconf_example.php');
		
		$this->assertTrue(is_array($cf->getVars()));
		
		$this->assertTrue(isset($cf->all));
	}

	/**
	 * test new instance with an array as param
	 */
	function testCreateInstanceWithArray()
	{
		$vars = array('test' => 'testvalue');
		$cf = new Peak\Config($vars);		
		
		$this->assertTrue(is_array($cf->getVars()));

		$this->assertTrue(isset($cf->test));
	}
	
	/**
	 * test new instance with php var file
	 */
	function testCreateInstanceWithArrayFile()
	{
		$vars = include dirname(__FILE__).'/ConfigTest/appconf_example.php';
		$cf = new Peak\Config($vars);		
		
		$this->assertTrue(is_array($cf->getVars()));

		$this->assertTrue(isset($cf->all));
	}

	/**
	 * test new instance with non-php var file
	 */
	function testCreateInstanceWithMiscFile()
	{
		$cf = new Peak\Config(dirname(__FILE__).'/../phpunit.xml');		
		
		$this->assertTrue(is_array($cf->getVars()));

		$this->assertTrue(count($cf) == 0);
	}

	/**
	 * test new instance with an random string
	 */
	function testCreateInstanceWithString()
	{
		$vars = 'test';
		$cf = new Peak\Config($vars);		
		
		$this->assertTrue(is_array($cf->getVars()));

		// should return 0 since instanciate object with a random string will try to load a file that do not exists
		$this->assertTrue(count($cf) == 0);
	}
}