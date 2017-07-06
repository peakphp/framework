<?php

namespace Peak\Climber\Cron;

use Doctrine\DBAL\Connection;
use Peak\Climber\Application;
use Peak\Climber\Cron\Exception\DatabaseNotFoundException;
use Peak\Climber\Cron\Exception\TablesNotFoundException;
use Peak\Di\ContainerInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class Executor
{
    /**
     * @var null|object
     */
    protected $conn;

    /**
     * @var Application
     */
    protected $app;

    /**
     * Constructor
     *
     * @param ContainerInterface|null $container
     * @param array $config
     * @throws DatabaseNotFoundException
     * @throws TablesNotFoundException
     */
    public function __construct(ContainerInterface $container = null, array $config = [])
    {

        $this->app = new Application($container, $config);

        new Bootstrap($this->app->conf('crondb'));

        $this->conn = Application::container()->get('CronDbConnection');

        // run some validation for cron system
        if (!Cron::hasDbConnection($this->conn)) {
            throw new DatabaseNotFoundException('No connection to a database has been found!');
        } elseif (!Cron::isInstalled($this->conn)) {
            throw new TablesNotFoundException('Cron system is not installed. Please, use command cron:install before using cron executor.');
        }
    }

    /**
     * Run cron job system
     */
    public function run()
    {

    }
}
