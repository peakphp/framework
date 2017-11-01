<?php

namespace Peak\Climber\Cron;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use Peak\Climber\Application;

class BootstrapDatabase
{
    /**
     * Constructor.
     */
    public function __construct(array $database_config)
    {
        Application::container()->add(
            CronSystem::connect($database_config),
            'CronDbConnection'
        );
    }
}
