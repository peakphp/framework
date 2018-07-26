<?php
use PHPUnit\Framework\TestCase;

use Peak\Routing\CustomRoute;
use Peak\Routing\Request;

class CustomRouteTest extends TestCase
{

    public function testCreate()
    {   
        $custom = new CustomRoute('login', 'admin ');

        $this->assertTrue($custom->controller === 'admin');
        $this->assertTrue($custom->action === '');
        $this->assertTrue($custom->getRegex() === 'login');

        $custom = new CustomRoute('login', 'admin', 'index');

        $this->assertTrue($custom->action === 'index');
    }

    public function testMatch()
    {   
        $custom = new CustomRoute('login', 'admin');
        $route = $custom->matchRequest(new Request('login'));
        $this->assertTrue($route instanceof \Peak\Routing\Route);

        $custom = new CustomRoute('login', 'admin');
        $route = $custom->matchRequest(new Request('logout'));
        $this->assertFalse($route instanceof \Peak\Routing\Route);

        $custom = new CustomRoute('login', 'admin');
        $route = $custom->matchRequest(new Request('baseroute/myproject/dashboard/login', 'baseroute/myproject/dashboard'));
        $this->assertTrue($route instanceof \Peak\Routing\Route);
    }

    public function testCustomRegex()
    {   
        $custom = new CustomRoute(':any', 'admin', 'index');
        $this->assertTrue($custom->getRegex() === '[^\/]+');
    }

    public function testChangeRegex()
    {   
        $custom = new CustomRoute(':alpha', 'admin', 'index');
        $this->assertTrue($custom->getRegex() === '[a-zA-Z]+');
    }
}