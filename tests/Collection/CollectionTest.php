<?php

use PHPUnit\Framework\TestCase;

use Peak\Collection\Collection;

class CollectionTest extends TestCase
{
    /**
	 * test new instance
	 */  
	function testCreateInstance()
	{
		$collection = new Collection([
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
	}


	function testInterfaceImplementation()
	{

		$collection = new Collection([
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

		$collection[] = 'test';

		$this->assertTrue(isset($collection[0]));
		$this->assertTrue($collection[0] === 'test');
	}

	/**
	 * New instance with mixed value
	 */
	function testCreateInstanceWithMixedValue()
	{
		$collection = new Collection("random");
		$this->assertTrue($collection->isEmpty());

		$collection = Collection::make(234);
		$this->assertTrue($collection->isEmpty());

		$collection = Collection::make(["test"]);
		$this->assertFalse($collection->isEmpty());

		$collection = new Collection(["test2"]);
		$this->assertFalse($collection->isEmpty());
	}
	
	/**
	 * Test array_ built in
	 */
	function testArrayBuiltInFuncs()
	{
		$collection = Collection::make([
		    [
		        'id' => 2135,
		        'first_name' => 'John',
		        'last_name' => 'Doe',
		    ],
		    [
		        'id' => 3245,
		        'first_name' => 'Sally',
		        'last_name' => 'Smith',
		    ],
		    [
		        'id' => 5342,
		        'first_name' => 'Jane',
		        'last_name' => 'Jones',
		    ],
		    [
		        'id' => 5623,
		        'first_name' => 'Peter',
		        'last_name' => 'Doe',
		    ]
		]);

		$chunk = Collection::make(
			$collection->chunk(2)
		);
		$this->assertTrue(count($chunk) == 2);

		$columns = Collection::make(
			$collection->column('first_name')
		);
		$this->assertTrue(count($columns) == 4);
		$this->assertTrue($columns[1] === 'Sally');
	}

    /**
     * Test __call exception
     */
	function testCallException()
    {
        $collection = Collection::make([
            'first_name' => 'John',
            'last name' => 'Doe',
        ]);

        try {
            $collection->unknow_method();
        } catch (\Exception $e) {
            $error = true;
        }

        $this->assertTrue(isset($error));
    }

    /**
     * Test map()
     */
	function testMap()
	{
		$collection = Collection::make([
	        'first_name' => 'John',
	        'last_name' => 'Doe',
	    ]);

	    $collection->map(function($n) {
	    	return strtoupper($n);
	    });

	    $this->assertTrue($collection->first_name === 'JOHN');
	}

    /**
     * Test toObject()
     */
	function testToObject()
	{
		$collection = Collection::make([
	        'first_name' => 'John',
	        'last name' => 'Doe',
	    ]);

	    $object = $collection->toObject();

	    $this->assertTrue($object instanceof \stdClass);
	    $this->assertTrue($object->first_name === 'John');
	    $this->assertTrue($object->{"last name"} === 'Doe');
	}

	/**
	 * Test read only
	 */
	function testReadOnly()
	{
		$collection = Collection::make([
	        'id' => 2135,
	        'first_name' => 'John',
	        'last_name' => 'Doe', 
		]);

		$collection->readOnly();

		$this->assertTrue($collection->isReadOnly());

		$collection->id = 2138;

		$this->assertFalse($collection->id == 2138);
		$this->assertTrue($collection->id == 2135);

		$collection['id'] = 2138;

		$this->assertFalse($collection->id == 2138);
		$this->assertTrue($collection->id == 2135);

		unset($collection->id);
		$this->assertTrue($collection->id == 2135);

		unset($collection['id']);
		$this->assertTrue($collection->id == 2135);
	}

    /**
     * Test jsonSerialize()
     */
	function testJsonSerialize()
    {
        $collection = Collection::make([
            'id' => 2135,
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        $json = $collection->jsonSerialize();

        $this->asserttrue(is_string($json));
        $this->assertTrue(is_array(json_decode($json, true)));
    }

    /**
     * Test mergeRecursiveDistinct()
     */
    public function testMerge()
    {
        $collection = Collection::make([
            'id' => 2135,
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        $unrelated_array = $collection->mergeRecursiveDistinct(['a' => 'test'], ['a' => 'test2', 'b' => 'foo'], true);

        $this->assertTrue(is_array($unrelated_array));
        $this->assertTrue($unrelated_array['a'] === 'test2');
    }

    /**
     * Test push()
     */
    public function testPush()
    {
        $collection = new Collection();
        $this->assertTrue(count($collection) == 0);
        $collection->push('foo');
        $this->assertTrue(count($collection) == 1);
        $collection->push('bar');
        $this->assertTrue(count($collection) == 2);
        $this->assertTrue($collection[0] === 'foo');
        $this->assertTrue($collection[1] === 'bar');
    }

    public function testStrip()
    {
        $array = ['test' => 'foobar'];
        $collection = new Collection($array);
        $this->assertTrue($collection->toArray() === $array);
        $collection->strip();
        $this->assertTrue($collection->toArray() === []);
    }

}