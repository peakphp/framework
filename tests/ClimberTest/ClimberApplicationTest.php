<?php

use PHPUnit\Framework\TestCase;

use Peak\Climber\Application;
use Peak\Bedrock\Application\Config;
use Peak\Climber\Commands\ClimberCronAddCommand;
use Peak\Climber\Cron\RegisterCommands;
use Peak\Climber\Cron\Exceptions\InvalidDatabaseConfigException;
use Peak\Climber\Cron\Exceptions\DatabaseNotFoundException;
use Peak\Climber\Cron\Exceptions\TablesNotFoundException;
use Symfony\Component\Console\Tester\CommandTester;
use Doctrine\DBAL\Exception\ConnectionException;

class ClimberApplicationTest extends TestCase
{

    protected function appNoCronDbConfig()
    {
        return new Application(null, [
            'env' => 'dev',
            'conf' => [
                FIXTURES_PATH . '/config/cli.yml',
            ]
        ]);
    }

    protected function appNoConnection()
    {
        return new Application(null, [
            'env' => 'dev',
            'conf' => [
                FIXTURES_PATH . '/config/cli.yml',
                FIXTURES_PATH . '/config/cron.database2.php',
            ]
        ]);
    }

    protected function appNoDatabase()
    {
        return new Application(null, [
            'env' => 'dev',
            'conf' => [
                FIXTURES_PATH . '/config/cli.yml',
                FIXTURES_PATH . '/config/cron.database3.php',
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


    /**
     * General test
     */
    function testApplication()
    {
        $app = $this->appNoTables();
        new RegisterCommands($app);

        $this->assertTrue($app instanceof Application);
        $this->assertTrue(Application::conf() instanceof Config);
        $this->assertTrue(Application::conf('php.date.timezone') === "America/Toronto");
    }

    /**
     * test Invalid configuration
     */
    function testNoDatabaseConfiguration()
    {
        $app = $this->appNoCronDbConfig();

        try {
            new RegisterCommands($app);
            $addcommand = Application::container()->create(ClimberCronAddCommand::class);
            $commandTester = new CommandTester($addcommand);
            $commandTester->execute([]);
        } catch(Exception $e) {
            $error = $e;
        }

        $this->assertTrue(isset($error));
        $this->assertTrue($error instanceof InvalidDatabaseConfigException);
    }

    /**
     * Connection to db fail
     */
    function testConnectionFail()
    {
        $app = $this->appNoConnection();

        try {
            new RegisterCommands($app);
            $addcommand = Application::container()->create(ClimberCronAddCommand::class);
            $commandTester = new CommandTester($addcommand);
            $commandTester->execute([]);
        } catch(Exception $e) {
            $error = $e;
        }

        $this->assertTrue(isset($error));
        $this->assertTrue($error instanceof ConnectionException);
    }

    /**
     * Test no tables found for cron
     */
    function testTablesNotFoundException()
    {
        // delete sqlite file before
        $sqlite_file = FIXTURES_PATH.'/database/cron.sqlite';
        if (file_exists($sqlite_file)) {
            unlink($sqlite_file);
        }

        $app = $this->appNoTables();

        try {
            new RegisterCommands($app);
            $addcommand = Application::container()->create(ClimberCronAddCommand::class);
            $commandTester = new CommandTester($addcommand);
            $commandTester->execute([]);
        } catch(Exception $e) {
            $error = $e;
        }

        $this->assertTrue(isset($error));
        $this->assertTrue($error instanceof TablesNotFoundException);
    }
}
