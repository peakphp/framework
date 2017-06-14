<?php

use PHPUnit\Framework\TestCase;

use Peak\Routing\RequestServerURI;
use Peak\Routing\Request;


class RequestServerURITest extends TestCase
{

    function testCreate()
    {
        $request = new RequestServerURI('/temp');
        $this->assertTrue($request->base_uri === '/temp/');
        $this->assertTrue(empty($request->raw_uri));
        $this->assertTrue($request->request_uri === '/');
    }
}