<?php

use PHPUnit\Framework\TestCase;

use Peak\Climber\Application;
use Peak\Climber\Commands\CronAddCommand;
use Peak\Climber\Cron\RegisterCommands;
use Symfony\Component\Console\Tester\CommandTester;

class ClimberApplicationTest extends TestCase
{

    function testApplication()
    {
        $application = new Application(null, [
            'env' => 'dev',
            'conf' => [
                FIXTURES_PATH . '/config/cli.yml',
                FIXTURES_PATH . '/config/cron.database.php',
            ]
        ]);

        new RegisterCommands($application);

        $container = Application::container();
        $addcommand = $container->instantiate(CronAddCommand::class);

        try {
            $commandTester = new CommandTester($addcommand);
            $commandTester->execute([]);
        } catch(Exception $e) {
            $error = true;
        }
        $this->assertTrue(isset($error));

        $this->assertTrue($application instanceof Application);
        $this->assertTrue(Application::conf() instanceof \Peak\Bedrock\Application\Config);
        $this->assertTrue(Application::conf('php.date.timezone') === "America/Toronto");
    }
}
