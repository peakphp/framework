<?php

use PHPUnit\Framework\TestCase;

use Peak\Config\FilesHandlers;

class FilesHandlersTest extends TestCase
{
    /**
     * @throws \Peak\Config\Exception\NoFileHandlersException
     */
    function testDefault()
    {
        $fileHandlers = new FilesHandlers();
        $this->assertTrue($fileHandlers->has('php'));
        $this->assertTrue($fileHandlers->getProcessor('php'));
    }

}
