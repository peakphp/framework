<?php

use PHPUnit\Framework\TestCase;

use Peak\Config\File\Json;

class JsonTest extends TestCase
{
    /**
     * Test load a file
     */
    function testLoadFile()
    {
        $config = new Json(__DIR__.'/../../fixtures/config/jsonfile.json');

        $this->assertTrue($config->has('widget.debug'));
        $this->assertTrue($config->get('widget.debug') === 'on');
        $this->assertTrue(is_null($config->get('widget2.debug')));
    }

    /**
     * Test load a file with comments
     */
    function testLoadFileWithComment()
    {
        $config = new Json(__DIR__.'/../../fixtures/config/jsonfilewithcomments.json', true);

        $this->assertTrue($config->has('widget.debug'));
        $this->assertTrue($config->get('widget.debug') === 'on');
        $this->assertTrue(is_null($config->get('widget2.debug')));
        $this->assertFalse($config->has('widget.image.name'));
        $this->assertTrue($config->has('widget.image.src'));
    }

    /**
     * Test Load unknown file
     */
    function testException()
    {
        try {
            $config =  new Json('unknow.file.abc');
        } catch (Exception $e) {

        }

        $this->assertFalse(isset($config));
    }


}