<?php

use PHPUnit\Framework\TestCase;

use Peak\Config\Loader\DefaultLoader;

class DefaultLoaderTest extends TestCase
{
    /**
     * @expectedException \Peak\Config\Exception\FileNotFoundException
     */
    public function testFileNotFoundException()
    {
        $callableProcessor = new DefaultLoader();
        $callableProcessor->load('unknownfile');
    }
}
