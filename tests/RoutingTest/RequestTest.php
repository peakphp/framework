<?php
use PHPUnit\Framework\TestCase;

use Peak\Routing\Request;

/**
 * @package    Peak\Resolve
 */
class RequestTest extends TestCase
{
    /**
     * init
     */ 
    function setUp()
    {
    }
    
    /**
     * unset
     */
    function tearDown()
    {
    }

    function testRequest()
    {

        $s = '/'; 
        Request::$separator = $s;

        $base_raw    = 'peak/framework';
        $request_raw = 'peak/framework/mypage';
        $request     = new Request($request_raw, $base_raw);

        $this->assertTrue($request->base_uri === $s.'peak/framework'.$s);
        $this->assertTrue($request->request_uri === $s.'mypage'.$s);
        $this->assertTrue($request->raw_uri === 'peak/framework/mypage');

    }

    function testSetBaseUri()
    {

        $s = '/'; 
        Request::$separator = $s;

        $base_raw    = 'peak/framework';
        $request_raw = 'peak/framework/mypage';
        $request     = new Request($request_raw, $base_raw);

        $request->setBaseUri('blog/category');
        $this->assertTrue($request->base_uri === $s.'blog/category'.$s);
    }

    function testSetRequestUri()
    {

        $s = '/'; 
        Request::$separator = $s;

        $base_raw    = 'peak/framework';
        $request_raw = 'peak/framework/mypage';
        $request     = new Request($request_raw, $base_raw);

        $request->setRequestUri('blog/category');
        $this->assertTrue($request->request_uri === $s.'blog/category'.$s);
    }
}