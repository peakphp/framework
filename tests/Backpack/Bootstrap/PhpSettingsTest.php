<?php

use \PHPUnit\Framework\TestCase;
use \Peak\Blueprint\Bedrock\Application;
use \Peak\Backpack\Bootstrap\PhpSettings;

class PhpSettingsTest extends TestCase
{
    function testBoot()
    {
        $app = $this->createMock(Application::class);
        $app
            ->method('getProp')
            ->will($this->returnValue([]));

        $phpSettings = new PhpSettings($app);
        $phpSettings->boot();

        $this->assertInstanceOf(\Peak\Blueprint\Common\Bootable::class, $phpSettings);
    }
}
