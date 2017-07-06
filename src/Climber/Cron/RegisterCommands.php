<?php

namespace Peak\Climber\Cron;

use Peak\Climber\Application;
use Peak\Climber\Commands\CronAddCommand;
use Peak\Climber\Commands\CronDelCommand;
use Peak\Climber\Commands\CronInstallCommand;
use Peak\Climber\Commands\CronListCommand;

class RegisterCommands
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * Constructor
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;

        $this->add([
            CronAddCommand::class,
            CronDelCommand::class,
            CronInstallCommand::class,
            CronListCommand::class,
        ]);
    }

    /**
     * Add commands to console application
     *
     * @param array $class
     */
    public function add(array $classes)
    {
        foreach ($classes as $class) {
            $this->app->add($this->app->container()->instantiate($class));
        }
    }
}
