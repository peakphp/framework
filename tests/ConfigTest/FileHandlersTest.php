<?php

use PHPUnit\Framework\TestCase;

use Peak\Config\FileHandlers;

class FileHandlersTest extends TestCase
{
    /**
     * Test PhpLoader
     */
    public function testDefaultHandler()
    {
        $fh = new FileHandlers('my.php');
        $handlers = $fh->get();
        $this->assertTrue(is_array($handlers));
        $this->assertTrue($handlers['loader'] === \Peak\Config\Loaders\PhpLoader::class);
        $this->assertTrue($handlers['processor'] === \Peak\Config\Processors\ArrayProcessor::class);
    }

    /**
     * @expectedException Peak\Config\Exceptions\NoFileHandlersException
     */
    public function testNoFileHandlersException()
    {
        $cf = new FileHandlers(FIXTURES_PATH.'/config/unknown.type');
    }

}

class CustomLoader
{

}