<?php

use \PHPUnit\Framework\TestCase;

require_once FIXTURES_PATH.'/collection/structures.php';

class StructureTest extends TestCase
{

    public function testNormal()
    {
        $entity = new MyStructure1();
        $entity->id = 12;
        $this->assertTrue($entity->id == 12);
        $this->assertTrue($entity->toArray() === ['id' => 12]);

        $entity = new MyStructure1();
        $entity->id = null;

        $this->assertTrue($entity->id === null);
        $this->assertTrue($entity->toArray() === ['id' => null]);
    }

    /**
     * @expectedException \Exception
     */
    public function testTypeError()
    {
        $entity = new MyStructure1();
        $entity->id = 'test';
    }

    /**
     * @expectedException \Exception
     */
    public function testTypeError2()
    {
        $entity = new MyStructure1();
        $entity->id;
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
        $this->assertFalse(isset($entity->id));
        $this->assertTrue(isset($entity->date));
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
    public function testFillUndefined()
    {
        $entity = new MyStructure1();
        $entity->date = new \DateTime();
        $this->assertFalse(isset($entity->id));
        $this->assertTrue(isset($entity->date));

        $entity->fillUndefinedWith('null');
        $this->assertTrue(isset($entity->id));
    }

}


