<?php

namespace Peak\Climber\Cron;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use Peak\Climber\Application;

class Bootstrap
{
    /**
     * Constructor.
     */
    public function __construct(array $database_config)
    {
        $conn = DriverManager::getConnection($database_config, new Configuration());
        Application::container()->add($conn, 'CronDbConnection');
    }
}
