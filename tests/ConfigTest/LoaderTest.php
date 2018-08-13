<?php

use PHPUnit\Framework\TestCase;

use \Peak\Config\Loader\TextLoader;

class LoaderTest extends TestCase
{
    /**
     * @expectedException \Peak\Config\Exception\FileNotFoundException
     */
    public function testFileNotFoundException()
    {
        $textLoader = new TextLoader();
        $textLoader->load('unknown.file');
    }
}