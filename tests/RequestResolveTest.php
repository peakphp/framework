<?php
use PHPUnit\Framework\TestCase;

use Peak\Routing\Request;
use Peak\Routing\RequestResolve;
use Peak\Routing\Route;

/**
 * @package    Peak\Resolve
 */
class RequestResolveTest extends TestCase
{
    /**
     * init view
     */ 
    function setUp()
    {
        //$this->peakview = new Peak\View();
    }
    
    /**
     * unset view
     */
    function tearDown()
    {
        unset($this->peakview);
    }
    
    /**
     * Create instance test
     */
    function testRequest()
    {

        Request::$separator = '/';

        $base = 'peak/framework';
        $request = 'peak/framework/index/index/test/';
        $request = new Request($request, $base);

        echo "\n";
        echo $request->base_uri;
        echo "\n";
        echo $request->raw_uri;
        echo "\n";
        echo $request->request_uri;
        echo "\n";

        $resolver = new RequestResolve($request);


        print_r($resolver->getRoute());
    }
}