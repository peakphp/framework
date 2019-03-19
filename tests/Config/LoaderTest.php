<?php

use PHPUnit\Framework\TestCase;

use \Peak\Config\Loader\TextLoader;

class LoaderTest extends TestCase
{
    public function testFileNotFoundException()
    {
        $this->expectException(\Peak\Config\Exception\FileNotFoundException::class);
        $textLoader = new TextLoader();
        $textLoader->load('unknown.file');
    }
}