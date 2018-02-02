<?php

use PHPUnit\Framework\TestCase;

use Peak\Bedrock\Application\Exceptions\ControllerNotFoundException;

class ControllerNotFoundExceptionTest extends TestCase
{
    /**
     * Test class creation
     */
    function testCreate()
    {
        $exception = new ControllerNotFoundException('controller_name');
    }

}