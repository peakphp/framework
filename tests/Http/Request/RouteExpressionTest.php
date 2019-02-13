<?php

use \PHPUnit\Framework\TestCase;
use \Peak\Http\Request\RouteExpression;

class RouteExpressionTest extends TestCase
{
    public function testCreate()
    {
        $route = new RouteExpression('/test/{id}');
    }
}
