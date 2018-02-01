<?php

use PHPUnit\Framework\TestCase;

use Peak\Di\Container;

require FIXTURES_PATH.'/app/Bootstrap.php';


class BootstrapperTest extends TestCase
{
    /**
     * Test class creation
     */
    function testCreate()
    {
        $bs = new Bootstrap(new Container());

        $this->assertTrue(isset($bs->init_method));
    }

}