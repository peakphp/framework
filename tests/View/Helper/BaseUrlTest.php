<?php

use \PHPUnit\Framework\TestCase;

use Peak\View\Helper\BaseUrl;

class BaseUrlTest extends TestCase
{
    public function testDefaultUsage()
    {
        $server = [
            'SERVER_NAME' => 'example.com',
        ];

        $baseUrl = new BaseUrl($server);

        $this->assertTrue($baseUrl() === '//example.com');
        $this->assertTrue($baseUrl('/') === '//example.com/');
        $this->assertTrue($baseUrl('test') === '//example.com/test');
        $this->assertTrue($baseUrl('/test', true) === 'http://example.com/test');

        $server = [
            'SERVER_NAME' => 'example.com',
            'HTTPS' => 'on'
        ];
        $baseUrl = new BaseUrl($server);
        $this->assertTrue($baseUrl('', true) === 'https://example.com');
    }

    public function testDefaultUsage2()
    {
        $server = [
            'SERVER_NAME' => 'example.com',
            'HTTP_HOST' => 'example.net',
            'HTTP_X_FORWARDED_HOST' => 'example.org'
        ];

        $baseUrl = new BaseUrl($server);

        $this->assertTrue($baseUrl() === '//example.net');
        $this->assertTrue($baseUrl('/') === '//example.net/');
        $this->assertTrue($baseUrl('/test') === '//example.net/test');
        $this->assertTrue($baseUrl('/test', true) === 'http://example.net/test');


        $this->assertTrue($baseUrl('/test', true, true) === 'http://example.org/test');
    }

    public function testDefaults()
    {
        $server = [
            'HOST' => 'example.com',
        ];

        $baseUrl = new BaseUrl($server);
        $baseUrl->addProtocolByDefault(true);

        $this->assertTrue($baseUrl() === 'http://example.com');
    }

    public function testPort()
    {
        $server = [
            'SERVER_NAME' => 'example.com',
            'SERVER_PORT' => 1234,
            'HTTPS' => 'on'
        ];

        $baseUrl = new BaseUrl($server);
        $this->assertTrue($baseUrl() === '//example.com:1234');

        $baseUrl->ignorePort(true);
        $this->assertTrue($baseUrl() === '//example.com');
    }

    public function testUseForward()
    {
        $server = [
            'SERVER_NAME' => 'example.com',
            'HTTP_HOST' => 'example.net',
            'HTTP_X_FORWARDED_HOST' => 'example.org'
        ];

        $baseUrl = new BaseUrl($server);
        $baseUrl->useForwardedHostByDefault(true);

        $this->assertTrue($baseUrl() === '//example.org');
    }
}
