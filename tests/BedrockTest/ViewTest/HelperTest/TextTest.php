<?php

use PHPUnit\Framework\TestCase;

use Peak\Bedrock\View\Helper\Text;

class TextTest extends TestCase
{
    function testInstantiate()
    {
        $text_helper = new Text();
        $string = 'abcdef';

        $new_string = $text_helper->truncate($string, 2, '');

        $this->assertTrue($new_string === 'ab');
    }
}
