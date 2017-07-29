<?php

namespace Peak\Climber\Cron;

use Peak\Climber\Application;
use Peak\Climber\Cron\Exception\InvalidDatabaseConfigException;

class RegisterCommands
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * Cron commands classes
     * @var array
     */
    protected $commands = [
        \Peak\Climber\Commands\CronAddCommand::class,
        \Peak\Climber\Commands\CronDelCommand::class,
        \Peak\Climber\Commands\CronInstallCommand::class,
        \Peak\Climber\Commands\CronListCommand::class,
        \Peak\Climber\Commands\CronRunCommand::class,
        \Peak\Climber\Commands\CronUpdateCommand::class,
    ];

    /**
     * Constructor
     * @param Application $app
     */
    public function __construct(Application $app, $prefix = 'cron')
    {
        $this->app = $app;

        if (!$this->app->conf()->has('cron.db') || !is_array($this->app->conf('cron.db'))) {
            throw new InvalidDatabaseConfigException(__CLASS__.': configuration [cron.db] is missing or invalid.');
        }

        new BootstrapDatabase($this->app->conf('cron.db'));

        $this->add($this->commands);
    }

    /**
     * Add commands to console application
     *
     * @param array $class
     */
    public function add(array $classes)
    {
        foreach ($classes as $class) {
            $this->app->add($this->app->container()->create($class));
        }
    }
}
