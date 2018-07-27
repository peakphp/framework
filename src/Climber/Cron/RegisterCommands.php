<?php

declare(strict_types=1);

namespace Peak\Climber\Cron;

use Peak\Climber\Application;
use Peak\Climber\Cron\Exceptions\InvalidDatabaseConfigException;

/**
 * Class RegisterCommands
 * @package Peak\Climber\Cron
 */
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
        \Peak\Climber\Commands\ClimberCronAddCommand::class,
        \Peak\Climber\Commands\ClimberCronDelCommand::class,
        \Peak\Climber\Commands\ClimberCronInstallCommand::class,
        \Peak\Climber\Commands\ClimberCronListCommand::class,
        \Peak\Climber\Commands\ClimberCronRunCommand::class,
        \Peak\Climber\Commands\ClimberCronUpdateCommand::class,
    ];

    /**
     * RegisterCommands constructor.
     * @param Application $app
     * @param string $prefix
     * @throws InvalidDatabaseConfigException
     * @throws \Peak\Di\Exception\NotFoundException
     */
    public function __construct(Application $app, $prefix = 'cron')
    {
        $this->app = $app;

        if (!$this->app->conf()->has('cron.db') || !is_array($this->app->conf('cron.db'))) {
            throw new InvalidDatabaseConfigException();
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
