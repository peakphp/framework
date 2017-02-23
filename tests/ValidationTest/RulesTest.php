<?php

use PHPUnit\Framework\TestCase;

/**
 * @package Peak\Validation\Rules
 */
class RulesTest extends TestCase
{
    /**
     * test empty
     */
    function testIsEmpty()
    {       
        $rule = new Peak\Validation\Rules\IsEmpty();

        $this->assertTrue($rule->validate(''));
        $this->assertFalse($rule->validate('not empty'));

        $empty1 = null;
        $empty2 = '';
        $empty3 = 0;
        $empty4 = 'not empty';

        $this->assertTrue($rule->validate($empty1));
        $this->assertTrue($rule->validate($empty2));
        $this->assertTrue($rule->validate($empty3));
        $this->assertFalse($rule->validate($empty4));
    }

    /**
     * test is not empty
     */
    function testIsNotEmpty()
    {       
        $rule = new Peak\Validation\Rules\IsNotEmpty();

        $this->assertFalse($rule->validate(''));
        $this->assertTrue($rule->validate('not empty'));

        $empty1 = null;
        $empty2 = '';
        $empty3 = 0;
        $empty4 = 'not empty';

        $this->assertFalse($rule->validate($empty1));
        $this->assertFalse($rule->validate($empty2));
        $this->assertFalse($rule->validate($empty3));
        $this->assertTrue($rule->validate($empty4));
    }

    /**
     * test is not empty
     */
    function testLength()
    {       
        $rule = new Peak\Validation\Rules\Length();

        $this->assertTrue($rule->validate(''));
        $this->assertTrue($rule->validate(0));
        $this->assertTrue($rule->validate(1));
        $this->assertTrue($rule->validate('random string'));
    }


}