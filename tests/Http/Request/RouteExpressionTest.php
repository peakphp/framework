<?php

use \PHPUnit\Framework\TestCase;
use \Peak\Http\Request\RouteExpression;

class RouteExpressionTest extends TestCase
{
    protected function getRegex($expression)
    {
        return (new RouteExpression($expression))->getRegex();
    }
    public function testCreate()
    {
        $route = new RouteExpression('/test');
        $this->assertTrue($route->getExpression() === '/test');
        $this->assertTrue($route->getRegex() === '/test[\/]?');
    }

    public function testTypedParams()
    {
        $dataSet = [
            '/test/{id}:num' => '/test/(?P<id>-?[0-9]+)[\/]?',
            '/{id}:num/{name}:alpha' => '/(?P<id>-?[0-9]+)/(?P<name>[a-zA-Z]+)[\/]?',
            '/{id}:num/{name}' => '/(?P<id>-?[0-9]+)/(?P<name>[^\/]+)[\/]?',
        ];

        foreach ($dataSet as $expression => $expectedRegex) {
            //echo "\n". $expectedRegex . "\n" . $this->getRegex($expression)."\n"."\n";
            $this->assertTrue($this->getRegex($expression) === $expectedRegex);
        }
    }
}
