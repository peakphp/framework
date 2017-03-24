<?php
use PHPUnit\Framework\TestCase;

class ApplicationTest extends TestCase
{
    /**
     * Create instance test
     */
    function testApplicationContainer()
    {
        \Peak\Bedrock\Application::setContainer(new \Peak\Di\Container);
        \Peak\Bedrock\Application::instantiate('\Peak\Bedrock\View');

        $this->assertTrue(\Peak\Bedrock\Application::container() instanceof \Peak\Di\ContainerInterface);
    }
    
}