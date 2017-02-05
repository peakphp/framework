<?php
use PHPUnit\Framework\TestCase;

/**
 * @package    Peak\Config
 */
class FileTest extends TestCase
{

    function testCreateObject()
    {
        $config =  new \Peak\Config\File(__DIR__.'/../fixtures/config/appconf_example.php');

        $this->assertTrue($config->have('all.php.display_errors'));
        $this->assertFalse($config->have('test.unknow'));
    }

    function testException()
    {
        try {
            $config =  new \Peak\Config\File('unknow.file.abc');
        }
        catch(Exception $e) {}

        $this->assertFalse(isset($config));
    }
          

}