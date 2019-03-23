<?php

use \PHPUnit\Framework\TestCase;

require_once FIXTURES_PATH . '/collection/structures.php';

class StructureTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testNormal()
    {
        $entity = new MyStructure1();
        $entity->id = 12;
        $this->assertTrue($entity->id == 12);
        $this->assertTrue($entity->toArray() === ['id' => 12, 'date' => null, 'obj' => null]);

        $entity = new MyStructure1();
        $entity->id = null;

        $this->assertTrue($entity->id === null);
        $this->assertTrue($entity->toArray() === ['id' => null, 'date' => null, 'obj' => null]);
    }

    public function testTypeError()
    {
        $this->expectException(\Exception::class);
        $entity = new MyStructure1();
        $entity->id = 'test';
    }

    public function testGetError()
    {
        $this->expectException(\Exception::class);
        $entity = new MyStructure1();
        $test = $entity->foobar;
    }

    /**
     * @throws Exception
     */
    public function testClassType()
    {
        $entity = new MyStructure1();
        $entity->date = new \DateTime();
        $this->assertTrue($entity->date instanceof \DateTime);
    }

    /**
     * @throws Exception
     */
    public function testObject()
    {
        $entity = new MyStructure1();
        $entity->obj = new \DateTime();
        $this->assertTrue($entity->obj instanceof \DateTime);
    }

    /**
     * @throws Exception
     */
    public function testIsset()
    {
        $entity = new MyStructure1();
        $entity->date = new \DateTime();

        $this->assertTrue(isset($entity->id));
        $this->assertTrue(isset($entity->date));
        $this->assertTrue(isset($entity->obj));
        $this->assertFalse(isset($entity->name));
    }

    /**
     * @throws Exception
     */
    public function testFromArray()
    {
        $entity = new MyStructure1([
            'id' => 34,
            'date' => new \DateTime()
        ]);
        $this->assertTrue($entity->date instanceof \DateTime);
    }

    /**
     * @throws Exception
     */
    public function testFromObject()
    {
        $test = new StdClass();
        $test->id = 34;
        $test->date = new \DateTime();

        $entity = new MyStructure1($test);
        $this->assertTrue($entity->date instanceof \DateTime);
    }

    /**
     * @throws Exception
     */
    public function testCreate()
    {
        $entity = MyStructure1::create([
            'id' => null,
            'date' => new \DateTime()
        ]);
        $this->assertTrue($entity->date instanceof \DateTime);

        $test = new StdClass();
        $test->id = 34;
        $test->date = new \DateTime();

        $entity = MyStructure1::create($test);
        $this->assertTrue($entity->id == 34);
    }

    /**
     * @throws Exception
     */
    public function testDefault()
    {
        $entity = new MyStructure2();
        $this->assertTrue($entity->obj === null);
        $this->assertTrue($entity->date === null);
        $this->assertTrue($entity->name === 'Foo');
    }

    /**
     * @throws Exception
     */
    public function testMultiple()
    {
        $entity = new MyStructure3();
        $entity->multiple = 123;
        $this->assertTrue($entity->multiple === 123);
        $entity->multiple = 'foobar';
        $this->assertTrue($entity->multiple === 'foobar');
        $entity->multiple = null;
        $this->assertTrue($entity->multiple === null);
    }

    /**
     * @throws Exception
     */
    public function testTypes()
    {
        $entity = new MyStructure4();
        $entity->array = [];
        $this->assertTrue(is_array($entity->array));
        $entity->float = 10.02;
        $this->assertTrue(gettype($entity->float) === 'double');
        $entity->boolean = false;
        $this->assertTrue(gettype($entity->boolean) === 'boolean');
        $entity->resource = fopen(__FILE__, 'r');
        $this->assertTrue(gettype($entity->resource) === 'resource');
        $entity->null = null;
        $this->assertTrue(gettype($entity->null) === 'NULL');
        $entity->any = 'test';
        $this->assertTrue(gettype($entity->any) === 'string');
    }

    /**
     * @throws Exception
     */
    public function testDataTypeChainingMultipleType()
    {
        $entity = new MyStructure5();
        $entity->multipleTypes1 = ['test'];
        $this->assertTrue(is_array($entity->multipleTypes1));
        $entity->multipleTypes1 = 'test';
        $this->assertTrue(is_string($entity->multipleTypes1));

        $entity->multipleTypes2 = 'test';
        $this->assertTrue(is_string($entity->multipleTypes2));
        $entity->multipleTypes2 = 10;
        $this->assertTrue(is_int($entity->multipleTypes2));
        $entity->multipleTypes2 = null;
        $this->assertTrue(is_null($entity->multipleTypes2));
    }

    /**
     * @throws Exception
     */
    public function testSetException()
    {
        $this->expectException(Exception::class);
        $entity = new MyStructure1([
            'id' => 34,
        ]);
        $entity->name = 'bob';
    }

    /**
     * @throws Exception
     */
    public function testSetException2()
    {
        $this->expectException(Exception::class);
        $entity = new MyStructure6([
            'wrongDefinition' => 'test',
        ]);
    }

    /**
     * @throws Exception
     */
    public function testConstructException()
    {
        $this->expectException(Exception::class);
        $entity = new MyStructure6('foobar');
    }

    /**
     * @throws Exception
     */
    public function testIterator()
    {
        $entity = new MyStructure1([
            'id' => 34,
        ]);
        $this->assertInstanceOf(ArrayIterator::class, $entity->getIterator());
    }
}
