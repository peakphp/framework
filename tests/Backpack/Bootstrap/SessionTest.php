<?php

use \PHPUnit\Framework\TestCase;
use \Peak\Blueprint\Bedrock\Application;
use \Peak\Backpack\Bootstrap\Session;

class SessionTest extends TestCase
{
    function testBoot()
    {
        $app = $this->createMock(Application::class);
        $app
            ->method('getProp')
            ->will($this->returnValue([]));

        $session = new Session($app);
        $session->boot();

        $this->assertInstanceOf(\Peak\Blueprint\Common\Bootable::class, $session);
    }
}
