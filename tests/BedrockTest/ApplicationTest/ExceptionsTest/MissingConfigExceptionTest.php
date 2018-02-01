<?php

use PHPUnit\Framework\TestCase;

use Peak\Bedrock\Application\Exceptions\MissingConfigException;

class MissingConfigExceptionTest extends TestCase
{
    /**
     * Test class creation
     */
    function testCreate()
    {
        $exception = new MissingConfigException('param1');
    }

}