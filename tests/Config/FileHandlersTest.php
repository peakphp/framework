<?php

use PHPUnit\Framework\TestCase;

use Peak\Config\FilesHandlers;

class FileHandlersTest extends TestCase
{
    public function testGetSet()
    {
        $fileHandlers = new FilesHandlers();
        $fileHandlers->set(
            'foo',
            \Peak\Config\Loader\PhpLoader::class,
            \Peak\Config\Processor\ArrayProcessor::class
        );
        $handlers = $fileHandlers->getAll();
        $this->assertTrue(is_array($handlers));
        $this->assertTrue(array_key_exists('foo', $handlers));
    }
}