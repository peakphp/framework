<?php

namespace Peak\Climber\Bootstrap;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use Peak\Bedrock\Application\Config;
use Peak\Climber\Application;
use Peak\Climber\Cron\InstallDatabase;
use \Exception;


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

        Application::container()->add($this->conn, 'DbConnection');

        $this->checkInstall();
    }

    /**
     * Check if cron database installed
     */
    public function checkInstall()
    {
        // check if table cron exists
        try {
            $this->conn->query("SELECT id FROM climber_cron LIMIT 1");
        } catch(Exception $e) {
            new InstallDatabase($this->conn, Application::conf('crondb.driver'));
        }
    }
}
