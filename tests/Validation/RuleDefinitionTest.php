<?php

use PHPUnit\Framework\TestCase;

use Peak\Validation\Rule;
use Peak\Validation\Definition\RuleDefinition;
use Peak\Validation\Definition\RuleArrayDefinition;

class RuleDefinitionTest extends TestCase
{
    /**
     * @throws Exception
     */
    function testCreateRuleWithRuleDefinition()
    {
        $rule = new Rule(
            new RuleDefinition('StrLength', [], null, null)
        );

        $this->assertInstanceOf(\Peak\Validation\Rule\StrLength::class, $rule->getValidator());
        $this->assertTrue(is_array($rule->getValidator()->getOptions()));
        $this->assertTrue($rule->getValidator()->getFlags() === null);
        $this->assertTrue($rule->getValidator()->getContext() === null);
    }

    /**
     * @throws Exception
     */
    function testCreateRuleWithRuleArrayDefinition()
    {
        $rule = new Rule(
            new RuleArrayDefinition([
                'rule' => 'StrLength',
                'options' => [],
                'flags' => null,
                'context' => [],
            ])
        );

        $this->assertInstanceOf(\Peak\Validation\Rule\StrLength::class, $rule->getValidator());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    function testInvalidArgumentException1()
    {
        new RuleArrayDefinition([]);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    function testInvalidArgumentException2()
    {
        new RuleArrayDefinition([
            'rule' => 'test',
            'options' => 1,
        ]);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    function testInvalidArgumentException3()
    {
        new RuleArrayDefinition([
            'rule' => 'test',
            'options' => [],
            'flags' => 'a',
        ]);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    function testInvalidArgumentException4()
    {
        new RuleArrayDefinition([
            'rule' => 'test',
            'error' => []
        ]);
    }
}