<?php

use PHPUnit\Framework\TestCase;

use Peak\Climber\Application;
use Peak\Bedrock\Application\Config;
use Peak\Climber\Commands\CronAddCommand;
use Peak\Climber\Cron\RegisterCommands;
use Peak\Climber\Cron\Exception\InvalidDatabaseConfigException;
use Peak\Climber\Cron\Exception\DatabaseNotFoundException;
use Peak\Climber\Cron\Exception\TablesNotFoundException;
use Symfony\Component\Console\Tester\CommandTester;

class ClimberApplicationTest extends TestCase
{

    protected function appNoConnection()
    {
        return new Application(null, [
            'env' => 'dev',
            'conf' => [
                FIXTURES_PATH . '/config/cli.yml',
            ]
        ]);
    }

    protected function appNoTables()
    {
        return new Application(null, [
            'env' => 'dev',
            'conf' => [
                FIXTURES_PATH . '/config/cli.yml',
                FIXTURES_PATH . '/config/cron.database.php',
            ]
        ]);
    }

    public function setup()
    {
//        $this->application = new Application(null, [
//            'env' => 'dev',
//            'conf' => [
//                FIXTURES_PATH . '/config/cli.yml',
//                FIXTURES_PATH . '/config/cron.database.php',
//            ]
//        ]);
//
//        $this->container = Application::container();
    }

    function testApplication()
    {
        $app = $this->appNoTables();
        new RegisterCommands($app);

        $this->assertTrue($app instanceof Application);
        $this->assertTrue(Application::conf() instanceof Config);
        $this->assertTrue(Application::conf('php.date.timezone') === "America/Toronto");
    }

    function testNoDatabaseConfiguration()
    {
        $app = $this->appNoConnection();

        try {
            new RegisterCommands($app);
            $addcommand = Application::container()->instantiate(CronAddCommand::class);
            $commandTester = new CommandTester($addcommand);
            $commandTester->execute([]);
        } catch(Exception $e) {
            $error = $e;
        }

        $this->assertTrue(isset($error));
        $this->assertTrue($error instanceof InvalidDatabaseConfigException);
    }
}
