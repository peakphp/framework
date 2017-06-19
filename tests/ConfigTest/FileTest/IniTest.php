<?php

use PHPUnit\Framework\TestCase;

use Peak\Config\File\Ini;

class IniTest extends TestCase
{
    /**
     * Test load a file
     */
    function testLoadFile()
    {
        $config = new Ini(__DIR__.'/../../fixtures/config/config.ini');

        $this->assertTrue($config->has('all.php.display_errors'));
        $this->assertTrue($config->get('all.php.display_errors') == 1);
        $this->assertTrue(is_null($config->get('this.that')));
    }

    /**
     * Test Load unknown file
     */
    function testException()
    {
        try {
            $config =  new Ini('unknow.file.abc');
        } catch (Exception $e) {

        }

        $this->assertFalse(isset($config));
    }


}