<?php

use PHPUnit\Framework\TestCase;

use Peak\Bedrock\View\Header;

class HeaderTest extends TestCase
{
    /**
     * Test code
     */
    function testCode()
    {
        $header = new Header();
        $msg = $header->codeAsStr(202);
        $this->assertTrue($msg === 'Accepted');
    }

    /**
     * Test set/has
     */
    function testSetHas()
    {
        $header = new Header();
        $field = 'Accept-Language: en-us';
        $header->set($field);
        $this->assertTrue($header->has($field));
        $this->assertFalse($header->has('Accept-Encoding: gzip, deflate'));

        $header = new Header();
        $fields = [
            'Accept-Language: en-us',
            'Content-Type: text/html',
        ];
        $header->set($fields);
        $this->assertTrue($header->has($fields[0]));
        $this->assertTrue($header->has($fields[1]));
        $this->assertFalse($header->has('Accept-Encoding: gzip, deflate'));
    }

    /**
     * Test noCache
     */
    function testNoCache()
    {
        $header = new Header();
        $field = 'Accept-Language: en-us';
        $header->set($field);
        $this->assertTrue($header->has($field));
        $this->assertFalse($header->has('Cache-Control: no-cache, must-revalidate'));

        $header->noCache();

        $this->assertTrue($header->has('Cache-Control: no-cache, must-revalidate'));
    }

    /**
     * Test noCache
     */
    function testSetContent()
    {
        $header = new Header();
        $field = 'Accept-Language: en-us';
        $header->set($field);
        $header->setContent('Hello World');

        ob_start();
        $header->release();
        $content = ob_get_clean();

        $this->assertTrue($content === 'Hello World');
    }

    /**
     * Test release
     */
    function testRelease()
    {
        $header = new Header();
        $header->holdOn();
        $header->release();
        $header->holdOff();
        $header->release();
    }
}