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
                'error' => 'Oops'
            ])
        );

        $this->assertInstanceOf(\Peak\Validation\Rule\StrLength::class, $rule->getValidator());
    }
}