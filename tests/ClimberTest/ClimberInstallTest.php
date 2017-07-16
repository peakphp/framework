<?php

use PHPUnit\Framework\TestCase;

use Peak\Climber\Application;
use Peak\Climber\Commands\CronInstallCommand;
use Peak\Climber\Cron\RegisterCommands;
use Symfony\Component\Console\Tester\CommandTester;

class ClimberInstallTest extends TestCase
{

    /**
     * @return Application using sqlite
     */
    protected function app()
    {
        return new Application(null, [
            'env' => 'dev',
            'conf' => [
                FIXTURES_PATH . '/config/cli.yml',
                FIXTURES_PATH . '/config/cron.database.php',
            ]
        ]);
    }

    /**
     * Test cron system install
     */
    function testInstallCron()
    {
        $app = $this->app();

        try {
            new RegisterCommands($app);
            $command = Application::container()->create(CronInstallCommand::class);
            $commandTester = new CommandTester($command);
            $commandTester->execute([]);
        } catch(Exception $e) {
            $error = true;
        }

        $this->assertFalse(isset($error));
    }


}
