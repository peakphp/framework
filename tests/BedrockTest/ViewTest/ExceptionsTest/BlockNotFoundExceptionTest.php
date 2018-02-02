<?php

use PHPUnit\Framework\TestCase;

use Peak\Bedrock\View\Exceptions\BlockNotFoundException;

class BlockNotFoundExceptionTest extends TestCase
{
    /**
     * Test class creation
     */
    function testCreate()
    {
        $exception = new BlockNotFoundException('block1');
    }

}