<?php

use PHPUnit\Framework\TestCase;

use Peak\Bedrock\Application;
use Peak\Bedrock\Controller\FrontController;

class FrontControllerTest extends TestCase
{
    /**
     * Test load front controller
     *
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    function testLoadFront()
    {
        $app = dummyApp();
    }

}