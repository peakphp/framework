<?php

use PHPUnit\Framework\TestCase;

use Peak\Validation\RuleBuilder;

class RuleBuilderTest extends TestCase
{
    /**
     * @throws Exception
     */
    function testCreateRule()
    {
        $rule = (new RuleBuilder('StrLength'))
            ->setOptions(['min' => 10])
            ->setContext('Hello')
            ->setFlags(0)
            ->build();

        $this->assertInstanceOf(\Peak\Validation\Rule\StrLength::class, $rule);
    }

    /**
     * @expectedException \Peak\Validation\Exception\NotFoundException
     */
    function testCreateRuleException()
    {
        $rule = (new RuleBuilder('unknownRule'))
            ->build();

        $this->assertInstanceOf(\Peak\Validation\Rule\StrLength::class, $rule);
    }
}
