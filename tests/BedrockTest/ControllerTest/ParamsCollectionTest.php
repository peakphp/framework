<?php

use PHPUnit\Framework\TestCase;

use \Peak\Bedrock\Controller\ParamsCollection;
use \Peak\Validation\Rule;

class ParamsCollectionTest extends TestCase
{
    /**
     * test create()
     */
    function testCreate()
    {
        $params = new ParamsCollection([
            'vote', 'id', 234234, 'note', 1
        ]);

        $this->assertTrue(isset($params[0]));

        $this->assertTrue(isset($params[0]));
        $this->assertTrue($params[0] === 'vote');
        $this->assertTrue(isset($params[1]));
        $this->assertTrue($params[1] === 'id');

        $this->assertTrue($params[1] === 'id');
        $this->assertTrue($params[2] == 234234);
        $this->assertTrue($params[3] === 'note');
        $this->assertTrue($params[4] == 1);

        $this->assertFalse(isset($params[5]));

        $this->assertTrue($params->vote === 'id');
        $this->assertTrue($params->id === 234234);
        $this->assertTrue($params->note === 1);
        $this->assertTrue($params->unknow == null);
    }

    /**
     * test has()
     */
    function testHas()
    {
        $params = new ParamsCollection([
            'vote', 'id', 234234, 'note', 'abc'
        ]);

        $this->assertTrue($params->has('vote'));
        $this->assertTrue($params->has(0));
        $this->assertTrue($params->has('id'));
        $this->assertTrue($params->has(1));
        $this->assertTrue($params->has(234234));
        $this->assertTrue($params->has(2));
        $this->assertTrue($params->has('note'));
        $this->assertTrue($params->has(3));

        $this->assertTrue($params->has(4));
        $this->assertFalse($params->has('abc'));
    }

    /**
     * test contains()
     */
    function testContains()
    {
        $params = new ParamsCollection([
            'vote', 'id', 234234, 'note', 'abc'
        ]);

        $this->assertTrue($params->contains('abc'));

        $this->assertTrue($params->contains('vote'));
        $this->assertTrue($params->contains('id'));
        $this->assertTrue($params->contains(234234));
        $this->assertTrue($params->contains('note'));

        $this->assertFalse($params->contains('order'));
    }

    /**
     * test has with rules()
     */
    function testHasRules()
    {
        $params = new ParamsCollection([
            'vote', 'id', 234234, 'note', 'abc'
        ]);

        $this->assertTrue(
            $params->has(
                'vote',
                Rule::create('Enum')->setOptions(['id', 'foo'])
            )
        );

        $this->assertFalse(
            $params->has('vote', Rule::create('Enum')
                ->setOptions(['id3', 'foo'])
            )
        );

        $this->assertFalse(
            $params->has('order', Rule::create('Enum')
                ->setOptions(['id3', 'foo'])
            )
        );
    }

}