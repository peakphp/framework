<?php

use PHPUnit\Framework\TestCase;

use Peak\Validation\Rule;

class RuleBuilderTest extends TestCase
{

    function testRule()
    {
        $rule = Rule::create('StrLength')->setOptions(['min' => 10]);
            
        $this->assertTrue($rule->get() instanceof Peak\Validation\Rules\StrLength);

        $rule = Rule::create('StrLength');

        $this->assertTrue($rule instanceof Peak\Validation\RuleBuilder);
    }   

    function testRuleBuilderValidate()
    {
        $pass = Rule::create('IntegerNumber')
            ->setOptions([
                'min_range' => 10,
                'max_range' => 50
            ])
            ->validate(20);

        $this->assertTrue($pass);

        $pass = Rule::create('IntegerNumber')
            ->setOptions([
                'min_range' => 10,
                'max_range' => 50
            ])
            ->validate(75);

        $this->assertFalse($pass);
    }

    function testCustomeRuleContext()
    {
        $pass = Rule::create(MatchContextRule::class)
            ->setContext('Hello')
            ->validate('Hello');

        $this->assertTrue($pass);

        $pass = Rule::create(MatchContextRule::class)
            ->setContext('Foobar')
            ->validate('Hello');

        $this->assertFalse($pass);
    }



    function testRuleBuilderError()
    {
        $error = 'Must be an integer';
        $rule = Rule::create('StrLength')->setError($error);

        $this->assertTrue($rule->validate(58));
        $this->assertTrue($rule->getError() === $error);
    }
}

class MatchContextRule extends \Peak\Validation\AbstractRule
{
    /**
     * Validate
     * 
     * @param  mixed $value
     * @return bool
     */
    public function validate($value)
    {
        return ($value === $this->context);
    }
}
