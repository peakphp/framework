<?php

use PHPUnit\Framework\TestCase;

use \Peak\Bedrock\Controller\RedirectionController;

class RedirectionControllerTest extends TestCase
{
    /**
     * test create()
     *
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    function testCreate()
    {
        $app = dummyApp();
        $redirect_controller = new Redir(
            new \Peak\Bedrock\View(),
            new \Peak\Bedrock\Application\Config(),
            new \Peak\Bedrock\Application\Routing()
        );
    }


}

class Redir extends RedirectionController
{

}