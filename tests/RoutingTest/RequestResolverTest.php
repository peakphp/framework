<?php
use PHPUnit\Framework\TestCase;

use Peak\Routing\Request;
use Peak\Routing\RequestResolver;
use Peak\Routing\Route;
use Peak\Routing\CustomRoute;
use Peak\Common\Collection;

class RequestResolverTest extends TestCase
{

    /**
     * Test empty request
     */
    function testEmptyRequest()
    {
        $base_uri = '';
        $request_uri = '';
        $request = new Request($request_uri, $base_uri);

        $this->assertEmpty($request->raw_uri);  
        $this->assertTrue($request->request_uri === '/');  
        $this->assertTrue($request->base_uri === '/'); 

        $base_uri = 'base';
        $request_uri = '';
        $request = new Request($request_uri, $base_uri);

        $this->assertEmpty($request->raw_uri);  
        $this->assertTrue($request->request_uri === '/');  
        $this->assertTrue($request->base_uri === '/base/');

    }
    
    /**
     * Test request
     */
    function testRequest()
    {

        Request::$separator = '/';

        $base = 'peak/framework';
        $request = 'peak/framework/test';
        $request = new Request($request, $base);

        $resolver = new RequestResolver($request);

        $route = $resolver->getRoute();
        $this->assertTrue($route instanceof Route);
        $this->assertTrue($route->controller === 'test');
    }

    /**
     * Test request with custom routes
     */
    function testRequestWithCustomRoutes()
    {
        $base = 'peak/framework';
        $request = 'peak/framework/id/15';
        $request = new Request($request, $base);
        $resolver = new RequestResolver($request);

        $customRoutes = new Collection([
            new CustomRoute('{id}:num', 'index', 'action'),
            new CustomRoute(':alpha', 'module', 'action')
        ]);

        $route = $resolver->getRoute($customRoutes);
        $this->assertTrue($route instanceof Route);
        $this->assertTrue($route->controller === 'index');
        $this->assertTrue($route->action === 'action');
    }
}