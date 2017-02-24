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
     * test string length
     */
    function testStrLength()
    {       
        $rule = new Peak\Validation\Rules\StrLength();

        $this->assertTrue($rule->validate(''));
        $this->assertTrue($rule->validate(0));
        $this->assertTrue($rule->validate(1));
        $this->assertTrue($rule->validate('random string'));

        $rule = new Peak\Validation\Rules\StrLength([
            'min' => 3,
        ]);

        $this->assertFalse($rule->validate(''));
        $this->assertFalse($rule->validate(0));
        $this->assertFalse($rule->validate(1));
        $this->assertTrue($rule->validate('random string'));


        $rule = new Peak\Validation\Rules\StrLength([
            'max' => 10
        ]);

        $this->assertTrue($rule->validate(''));
        $this->assertTrue($rule->validate(0));
        $this->assertTrue($rule->validate(1));
        $this->assertFalse($rule->validate('random string'));

        $rule = new Peak\Validation\Rules\StrLength([
            'min' => 2,
            'max' => 10
        ]);

        $this->assertFalse($rule->validate(''));
        $this->assertFalse($rule->validate(0));
        $this->assertTrue($rule->validate('22'));
        $this->assertTrue($rule->validate('0123456789'));
        $this->assertFalse($rule->validate('random string'));
    }


    /**
     * test enum
     */
    function testEnum()
    {       
        $rule = new Peak\Validation\Rules\Enum([
            'foo', 'bar', 'barfoo'
        ]);

        $this->assertFalse($rule->validate(''));
        $this->assertTrue($rule->validate('bar'));
        $this->assertFalse($rule->validate('foobar'));
        $this->assertTrue($rule->validate('barfoo'));
    }

    /**
     * test int
     */
    function testInt()
    {       
        $rule = new Peak\Validation\Rules\Int();

        $this->assertTrue($rule->validate(-1554));
        $this->assertTrue($rule->validate(8880));
        $this->assertTrue($rule->validate(6458));

        $this->assertFalse($rule->validate(''));
        $this->assertFalse($rule->validate('a6458'));
        $this->assertFalse($rule->validate('asdasdasd'));


        $rule = new Peak\Validation\Rules\Int([
            'min_range' => 10,
            'max_range' => 20,
        ]);

        $this->assertTrue($rule->validate(15));
        $this->assertFalse($rule->validate(25));

        $rule = new Peak\Validation\Rules\Int([
            'min_range' => 10,
        ], FILTER_FLAG_ALLOW_HEX);

        $this->assertTrue($rule->validate(15));
        $this->assertTrue($rule->validate("0x0000FF"));

        $rule = new Peak\Validation\Rules\Int([
            'min_range' => 10,
        ]);

        $this->assertTrue($rule->validate(15));
        $this->assertTrue($rule->validate(0x0000FF));
        $this->assertFalse($rule->validate("0x0000FF"));
    }

    /**
     * test float
     */
    function testFloat()
    {       
        $rule = new Peak\Validation\Rules\Float();

        $this->assertTrue($rule->validate(-1554.55));
        $this->assertTrue($rule->validate(8880.97475));
        $this->assertTrue($rule->validate("6458.3564"));

        $this->assertFalse($rule->validate('0.0.0'));
        $this->assertFalse($rule->validate('a6458'));
        $this->assertFalse($rule->validate('asdasdasd'));


        $rule = new Peak\Validation\Rules\Float(['decimal' => ',']);
        $this->assertTrue($rule->validate('2,59'));
        $this->assertFalse($rule->validate(1.58));

    }


}