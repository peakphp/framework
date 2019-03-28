<?php

use \PHPUnit\Framework\TestCase;
use \Peak\Bedrock\Http\Application;
use \Peak\Backpack\Bootstrap\Routing;

class RoutingTest extends TestCase
{
    function testBoot()
    {
        $app = new Application(
            $this->createMock(\Peak\Bedrock\Kernel::class),
            $this->createMock(\Peak\Http\Request\HandlerResolver::class),
            new Peak\Collection\DotNotationCollection([
                'routes_path_prefix' => '',
                'routes' => [
                    [
                        'path' => '/',
                        'method' => 'GET',
                        'stack' => function() {}
                    ]
                ]
            ])
        );

        $routing = new Routing($app);
        $routing->boot();

        $this->assertInstanceOf(\Peak\Blueprint\Common\Bootable::class, $routing);
    }

    function testNoRoutes()
    {
        $app = new Application(
            $this->createMock(\Peak\Bedrock\Kernel::class),
            $this->createMock(\Peak\Http\Request\HandlerResolver::class),
           null
        );

        $routing = new Routing($app);
        $routing->boot();
        $this->assertInstanceOf(\Peak\Blueprint\Common\Bootable::class, $routing);
    }

    function testBootException1()
    {
        $this->expectException(\Exception::class);
        $app = new Application(
            $this->createMock(\Peak\Bedrock\Kernel::class),
            $this->createMock(\Peak\Http\Request\HandlerResolver::class),
            new Peak\Collection\DotNotationCollection([
                'routes_path_prefix' => '',
                'routes' =>  [
                    '123'
                ]
            ])
        );

        $routing = new Routing($app);
        $routing->boot();
    }

    function testBootException2()
    {
        $this->expectException(\Exception::class);
        $app = new Application(
            $this->createMock(\Peak\Bedrock\Kernel::class),
            $this->createMock(\Peak\Http\Request\HandlerResolver::class),
            new Peak\Collection\DotNotationCollection([
                'routes_path_prefix' => '',
                'routes' => [
                    [
                    ]
                ]
            ])
        );

        $routing = new Routing($app);
        $routing->boot();
    }

    function testBootException3()
    {
        $this->expectException(\Exception::class);
        $app = new Application(
            $this->createMock(\Peak\Bedrock\Kernel::class),
            $this->createMock(\Peak\Http\Request\HandlerResolver::class),
            new Peak\Collection\DotNotationCollection([
                'routes_path_prefix' => '',
                'routes' => [
                    [
                        'path' => 's'
                    ]
                ]
            ])
        );

        $routing = new Routing($app);
        $routing->boot();
    }

    function testBootException4()
    {
        $this->expectException(\Exception::class);
        $app = new Application(
            $this->createMock(\Peak\Bedrock\Kernel::class),
            $this->createMock(\Peak\Http\Request\HandlerResolver::class),
            new Peak\Collection\DotNotationCollection([
                'routes_path_prefix' => '',
                'routes' => 'hello'
            ])
        );

        $routing = new Routing($app);
        $routing->boot();
    }
}
