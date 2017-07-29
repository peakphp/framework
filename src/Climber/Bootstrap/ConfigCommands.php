<?php

namespace Peak\Climber\Bootstrap;

use Peak\Climber\Application;

class ConfigCommands
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

        if (!$app->conf()->has('commands') || !is_array($app->conf('commands'))) {
            return;
        }

        $this->add($app->conf('commands'));
    }

    /**
     * Add commands to console application
     *
     * @param array $class
     */
    public function add(array $classes)
    {
        foreach ($classes as $class) {
            $this->app->add(
                $this->app->container()->create($class)
            );
        }
    }
}
