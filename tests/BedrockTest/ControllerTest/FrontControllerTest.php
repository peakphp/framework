<?php

use PHPUnit\Framework\TestCase;

use Peak\Bedrock\Application;
use Peak\Bedrock\Controller\FrontController;

class FrontControllerTest extends TestCase
{

    protected $app;

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