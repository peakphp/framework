<?php

use PHPUnit\Framework\TestCase;

use Peak\Bedrock\Application;
use Peak\Bedrock\Application\Bootstrap\ConfigPHP;

class ConfigPHPTest extends TestCase
{
    /**
     * Test bootstrap class
     *
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    function testBootstrap()
    {
        ini_set('display_errors', 0);
        ini_set('display_startup_errors', 0);
        $this->assertTrue(ini_get('display_errors') == 0);
        $app = dummyApp();
        Application::create(ConfigPHP::class);
        $this->assertTrue(ini_get('display_errors') == 1);
        $this->assertTrue(ini_get('display_startup_errors') == 1);
        $this->assertTrue(ini_get('date.timezone') === "America/Toronto");
    }

    /**
     * Test bootstrap class
     *
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    function testBootstrap2()
    {
        ini_set('display_errors', 0);
        ini_set('display_startup_errors', 0);
        $this->assertTrue(ini_get('display_errors') == 0);
        $app = dummyApp();
        ini_set('display_errors', 0);
        ini_set('display_startup_errors', 0);
        Application::conf()->set('php', []); // empty php config
        Application::create(ConfigPHP::class);
        $this->assertTrue(ini_get('display_errors') == 0);
        $this->assertTrue(ini_get('display_startup_errors') == 0);
    }
}