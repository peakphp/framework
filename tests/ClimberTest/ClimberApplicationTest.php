<?php
use PHPUnit\Framework\TestCase;

use Peak\Climber\Application;

class ClimberApplicationTest extends TestCase
{

    function testApplication()
    {
        try {
            $application = new Application(null, [
                'env' => 'dev',
                'conf' => [
                    FIXTURES_PATH . '/config/cli.yml'
                ]
            ]);
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        $this->assertTrue($application instanceof Application);
        $this->assertTrue(Application::conf() instanceof \Peak\Bedrock\Application\Config);
        $this->assertTrue(Application::conf('php.date.timezone') === "America/Toronto");
    }
}