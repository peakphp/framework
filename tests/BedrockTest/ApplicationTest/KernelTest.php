<?php

use PHPUnit\Framework\TestCase;

use Peak\Bedrock\Application;
use Peak\Bedrock\Controller\FrontController;

class KernelTest extends TestCase
{
    /**
     * Test reload kernel
     *
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    function testReload()
    {
        $app = dummyApp();
        $app->kernel()->reload();
    }

    /**
     * Test reload kernel
     *
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    function testRun()
    {
        $app = dummyApp();
        //$app->run();
    }

}