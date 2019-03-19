<?php

use PHPUnit\Framework\TestCase;

use Peak\Config\Loader\PhpLoader ;

class PhpLoaderTest extends TestCase
{
    public function testFileNotFoundException()
    {
        $this->expectException(\Peak\Config\Exception\FileNotFoundException::class);
        $callableProcessor = new PhpLoader();
        $callableProcessor->load('unknownfile');
    }
}
