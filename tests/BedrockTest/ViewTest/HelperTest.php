<?php

use PHPUnit\Framework\TestCase;

use Peak\Bedrock\View;

require FIXTURES_PATH.'/view/TestHelper.php';

class HelperTest extends TestCase
{
    /**
     * Test creation
     */
    function testCreate()
    {
        $test_helper = new TestHelper(new View());
    }
}
