<?php

use Peak\Backpack\AppBuilder;
use Peak\Backpack\AppRoutesMapper;
use \PHPUnit\Framework\TestCase;

require_once FIXTURES_PATH . '/application/HandlerA.php';

class AppRoutesMapperTest extends TestCase
{
    public function testCreateApp()
    {
        /** @var \Peak\Bedrock\Http\Application $app */
        $app = $app = (new AppBuilder())->build();

        $app->get('/test1', HandlerA::class);
        $app->post('/test2', [
            HandlerA::class,
            new HandlerA(),
            new \Peak\Http\Stack([
                HandlerB::class
            ], new \Peak\Http\Request\HandlerResolver(null))
        ]);
        $app->get('/test3', HandlerA::class);

        $appRoutesMapper = new AppRoutesMapper();
        $routes = $appRoutesMapper->inspect($app);

        $this->assertTrue(is_array($routes));
        $this->assertTrue(count($routes) == 3);
        $this->assertTrue($routes[1]['path'] === '/test2');
    }
}
