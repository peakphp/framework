<?php

use PHPUnit\Framework\TestCase;

use Peak\Common\TextUtils;

class TextUtilsTest extends TestCase
{
    function testCountWords()
    {
        $this->assertTrue(TextUtils::countWords('Lorem ispum') == 2);
        //should be 7 words in french, but for now ,we just calculate it in the english way
        $this->assertTrue(TextUtils::countWords('L\'été est arrivé. C\'est super') == 5);
    }

    function testCountParagraphs()
    {
        $this->assertTrue(TextUtils::countParagraphs("Lorem ispum.\nLorem ipsum dolor sit.\nUt enim ad.\nExcepteur sint!") == 4);
        $this->assertTrue(TextUtils::countParagraphs("L'été est arrivé!\r\nYoupi!") == 2);
    }

    function testCountChars()
    {
        $string1 = 'Lorem ispum. Lorem ipsum dolor sit. Ut enim ad. Excepteur sint';
        $string2 = 'Lorem ipsum';

        $this->assertTrue(TextUtils::countChars($string1) == 52);
        $this->assertTrue(TextUtils::countChars($string2) == 10);
        $this->assertTrue(TextUtils::countChars($string1, true) == 62);
        $this->assertTrue(TextUtils::countChars($string2, true) == 11);
    }

    function testTruncate()
    {
        $string = 'Lorem ispum. Lorem ipsum dolor sit. Ut enim ad. Excepteur sint';

        $truncate = TextUtils::truncate($string, 10);
        $this->assertTrue($truncate === 'Lorem...');

        $truncate = TextUtils::truncate($string, 8);
        $this->assertTrue($truncate === 'Lorem...');

        $truncate = TextUtils::truncate($string, 10, '---');
        $this->assertTrue($truncate === 'Lorem---');

        $truncate = TextUtils::truncate($string, 10, '...', true);
        $this->assertTrue($truncate === 'Lorem i...');

        $truncate = TextUtils::truncate($string, 20, '...', true, true);
        $this->assertTrue($truncate === 'Lorem is...eur sint');
    }

    function testWordwrap()
    {
        $string = 'Lorem ispum. Lorem ipsum dolor sit. Ut enim ad. Excepteur sint';
        $wrap = TextUtils::wordwrap($string,10, '<br>');
        $this->assertTrue($wrap === 'Lorem<br>ispum.<br>Lorem<br>ipsum<br>dolor sit.<br>Ut enim<br>ad.<br>Excepteur<br>sint');

        $wrap = TextUtils::wordwrap($string,5, '<br>',true);
        $this->assertTrue($wrap === 'Lorem<br>ispum<br>.<br>Lorem<br>ipsum<br>dolor<br>sit.<br>Ut<br>enim<br>ad.<br>Excep<br>teur<br>sint');
    }
}