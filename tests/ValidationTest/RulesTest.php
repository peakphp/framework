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
     * test integer
     */
    function testIntegerNumber()
    {       
        $rule = new Peak\Validation\Rules\IntegerNumber();

        $this->assertTrue($rule->validate(-1554));
        $this->assertTrue($rule->validate(8880));
        $this->assertTrue($rule->validate(6458));

        $this->assertFalse($rule->validate(''));
        $this->assertFalse($rule->validate('a6458'));
        $this->assertFalse($rule->validate('asdasdasd'));


        $rule = new Peak\Validation\Rules\IntegerNumber([
            'min_range' => 10,
            'max_range' => 20,
        ]);

        $this->assertTrue($rule->validate(15));
        $this->assertFalse($rule->validate(25));

        $rule = new Peak\Validation\Rules\IntegerNumber([
            'min_range' => 10,
        ], FILTER_FLAG_ALLOW_HEX);

        $this->assertTrue($rule->validate(15));
        $this->assertTrue($rule->validate("0x0000FF"));

        $rule = new Peak\Validation\Rules\IntegerNumber([
            'min_range' => 10,
        ]);

        $this->assertTrue($rule->validate(15));
        $this->assertTrue($rule->validate(0x0000FF));
        $this->assertFalse($rule->validate("0x0000FF"));
    }

    /**
     * test float
     */
    function testFloatNumber()
    {       
        $rule = new Peak\Validation\Rules\FloatNumber();

        $this->assertTrue($rule->validate(-1554.55));
        $this->assertTrue($rule->validate(8880.97475));
        $this->assertTrue($rule->validate("6458.3564"));

        $this->assertFalse($rule->validate('0.0.0'));
        $this->assertFalse($rule->validate('a6458'));
        $this->assertFalse($rule->validate('asdasdasd'));


        $rule = new Peak\Validation\Rules\FloatNumber(['decimal' => ',']);
        $this->assertTrue($rule->validate('2,59'));
        $this->assertFalse($rule->validate(1.58));

    }

    /**
     * test Email
     */
    function testEmail()
    {       
        $rule = new Peak\Validation\Rules\Email();

        $this->assertTrue($rule->validate("a@a.a"));
        $this->assertFalse($rule->validate("bob@aa"));
        $this->assertFalse($rule->validate("b b@a.a"));
        $this->assertFalse($rule->validate("a@ a.a"));
    }

    /**
     * test Url
     */
    function testUrl()
    {       
        $rule = new Peak\Validation\Rules\Url();

        $this->assertTrue($rule->validate("http://test.com"));
        $this->assertTrue($rule->validate("http://test"));
        $this->assertFalse($rule->validate("test.com"));

    }

    /**
     * test Regex
     */
    function testRegex()
    {       
        $rule = new Peak\Validation\Rules\Regex(['regexp' => '#^[A-Z]$#']);

        $this->assertTrue($rule->validate("A"));
        $this->assertTrue($rule->validate("B"));
        $this->assertFalse($rule->validate("BB"));
        $this->assertFalse($rule->validate("a"));

    }

    /**
     * test Alpha
     */
    function testAlpha()
    {       
        $rule = new Peak\Validation\Rules\Alpha();

        $this->assertTrue($rule->validate("A"));
        $this->assertTrue($rule->validate("B"));
        $this->assertTrue($rule->validate("BB"));
        $this->assertTrue($rule->validate("s"));

        $this->assertFalse($rule->validate("0A"));

    }

    /**
     * test AlphaNum
     */
    function testAlphaNum()
    {       
        $rule = new Peak\Validation\Rules\AlphaNum();

        $this->assertTrue($rule->validate("A3"));
        $this->assertTrue($rule->validate("B4"));
        $this->assertTrue($rule->validate("BB0"));
        $this->assertTrue($rule->validate("012"));

        $this->assertFalse($rule->validate("123a 2a"));

    }

    /**
     * test DateTime
     */
    function testDateTime()
    {       
        $rule = new Peak\Validation\Rules\DateTime();

        $this->assertTrue($rule->validate("2012-02-28 12:11:20"));
        $this->assertFalse($rule->validate("2012-02-30 11:00:02"));

        $this->assertFalse($rule->validate("2012-02-30"));

        $rule = new Peak\Validation\Rules\DateTime([
            'format' => 'd/m/Y'
        ]);

        $this->assertTrue($rule->validate("28/02/2017"));
        $this->assertFalse($rule->validate("28/02/2017 15:32:11"));
    }


}