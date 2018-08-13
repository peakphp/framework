<?php

use PHPUnit\Framework\TestCase;

use Peak\Validation\Rule;
use Peak\Validation\AbstractRule;

class RuleBuilderTest extends TestCase
{

    function testRule()
    {
        $rule = Rule::create('StrLength')->setOptions(['min' => 10]);
            
        $this->assertTrue($rule->get() instanceof Peak\Validation\Rules\StrLength);

        $rule = Rule::create('StrLength');

        $this->assertTrue($rule instanceof Peak\Validation\RuleBuilder);
    }

    function testRuleGet()
    {
        $rule = Rule::create('StrLength');

        $rule->setContext('test');
        $this->assertTrue($rule->getContext() === 'test');
        $this->assertTrue(empty($rule->getFlags()));
        $this->assertTrue(empty($rule->getOptions()));
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

    function testRuleBuilderEnum()
    {
        $pass = Rule::create(\Peak\Validation\Rules\Enum::class)
            ->setOptions(['allo', 'Hello'])
            ->validate('Hello');

        $this->assertTrue($pass);
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

    function testRuleStaticCall()
    {
        $this->assertTrue(Rule::isEmpty(''));
        $this->assertTrue(Rule::isNotEmpty('foobar'));
        $this->assertTrue(Rule::integerNumber(10, [
            'min_range' => 10,
            'max_range' => 50
        ]));
        $this->assertFalse(Rule::integerNumber(5, [
            'min_range' => 10,
            'max_range' => 50
        ]));

        $this->assertTrue(Rule::integerNumber("0x0000FF", [
            'min_range' => 10,
        ], FILTER_FLAG_ALLOW_HEX));

        $this->assertTrue(Rule::integerNumber("0x0000FF", [
            'min_range' => 10,
        ], FILTER_FLAG_ALLOW_HEX, 'random context data'));
    }

    function testException()
    {
        try {
            $rule = Rule::create('UnknownRule')->get();
        } catch (Exception $e) {
            $error = true;
        }
        $this->assertTrue(isset($error));
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
