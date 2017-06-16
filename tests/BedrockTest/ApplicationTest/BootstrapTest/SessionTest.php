<?php

use PHPUnit\Framework\TestCase;

use Peak\Bedrock\Application;
use Peak\Bedrock\Application\Bootstrap\Session;

class SessionTest extends TestCase
{
    /**
     * Test bootstrap class
     *
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    function testBootstrap()
    {
        // just for the kick, should do nothing because it's executed in cli mode
        $app = dummyApp();
        Application::instantiate(Session::class);
    }
}