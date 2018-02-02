<?php

use PHPUnit\Framework\TestCase;

use Peak\Bedrock\Application\Exceptions\NoRouteFoundException;

class NoRouteFoundExceptionTest extends TestCase
{
    /**
     * Test class creation
     */
    function testCreate()
    {
        $exception = new NoRouteFoundException('request');
    }

}