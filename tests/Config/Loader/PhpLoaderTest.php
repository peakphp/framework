<?php

use PHPUnit\Framework\TestCase;

use Peak\Config\Loader\PhpLoader ;

class PhpLoaderTest extends TestCase
{
    /**
     * @expectedException \Peak\Config\Exception\FileNotFoundException
     */
    public function testFileNotFoundException()
    {
        $callableProcessor = new PhpLoader();
        $callableProcessor->load('unknownfile');
    }
}
