<?php
use PHPUnit\Framework\TestCase;

/**
 * @package    Peak\Config
 */
class CollectionTest extends TestCase
{
	
	/**
	 * instanciate class for tests
	 */
	function setUp()
	{		
		
	}
		 
	/**
	 * test new instance
	 */  
	function testCreateInstance()
	{
		$collection = new Peak\Collection([
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
		$this->assertTrue($collection['age'] == 75);
		$this->assertTrue(isset($collection['passions']));
		$this->assertFalse(isset($collection['sport']));
		$this->assertTrue(isset($collection['active']));

		//offset set
		$collection['age'] = 87;
		$this->assertTrue($collection['age'] == 87);

		//count
		$this->assertTrue(count($collection) == 5);
		$this->assertTrue(count($collection['passions']) == 3);

		//unset
		unset($collection['age']);
		$this->assertFalse(isset($collection['age']));
		$this->assertTrue(count($collection) == 4);

		//iterator
		$i = 0;
		foreach($collection as $k => $v) {
			++$i;
		}
		$this->assertTrue($i == 4);

		//access to array as object
		$this->assertTrue($collection->name === 'Bob Ball');

		//unset with object syntax
		unset($collection->name);
		$this->assertTrue(count($collection) == 3);

		//isset with object syntax
		$this->assertFalse(isset($collection->name));
		$this->assertTrue(isset($collection->nick));
	}
	
	
}