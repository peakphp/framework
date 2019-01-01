<?php

use \PHPUnit\Framework\TestCase;

require_once FIXTURES_PATH.'/collection/structures.php';

class ImmutableStructureTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testNormal()
    {
        $entity = new MyStructure2([
            'id' => 34,
            'date' => new \DateTime()
        ]);
        $this->assertTrue($entity->date instanceof \DateTime);
    }

    /**
     * @expectedException \Exception
     */
    public function testSet()
    {
        $entity = new MyStructure2([
            'id' => 34,
            'date' => new \DateTime()
        ]);
        $entity->id = 35;
    }
}
