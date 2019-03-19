<?php

use \PHPUnit\Framework\TestCase;
use \Peak\Bedrock\Http\Application;
use \Peak\Backpack\Bootstrap\Routing;

class RoutingTest extends TestCase
{
    function testBoot()
    {
        $app = $this->createMock(Application::class);
        $app
            ->method('getProp')
            ->will($this->returnValue([
                [
                    'path' => '/',
                    'method' => 'GET',
                    'stack' => function() {}
                ]
            ]));

        $routing = new Routing($app);
        $routing->boot();

        $this->assertInstanceOf(\Peak\Blueprint\Common\Bootable::class, $routing);
    }
}
