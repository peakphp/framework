<?php

use PHPUnit\Framework\TestCase;

use Peak\Config\FilesHandlers;

class FilesHandlersTest extends TestCase
{

    /**
     * Test getAll()
     */
    function testGetAll()
    {
        $handlers = FilesHandlers::getAll();
        $this->assertTrue(is_array($handlers));
    }

}