<?php

namespace Peak\Climber\Bootstrap;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use Peak\Bedrock\Application\Config;
use Peak\Climber\Application;

class ConfigCronDb
{
    /**
     * Cron database connection
     * @var \Doctrine\DBAL\Connection
     */
    protected $conn;

    /**
     * Constructor.
     */
    public function __construct(Config $conf)
    {
        if (!isset($conf->crondb)) {
            return;
        }

        $this->conn = DriverManager::getConnection($conf->crondb, new Configuration());

        Application::container()->add($this->conn, 'CronDbConnection');
    }
}
