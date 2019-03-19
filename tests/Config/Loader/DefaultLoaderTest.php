<?php

use PHPUnit\Framework\TestCase;

use Peak\Config\Loader\DefaultLoader;

class DefaultLoaderTest extends TestCase
{
    public function testFileNotFoundException()
    {
        $this->expectException(\Peak\Config\Exception\FileNotFoundException::class);
        $callableProcessor = new DefaultLoader();
        $callableProcessor->load('unknownfile');
    }
}
