<?php

namespace Peak\Climber\Cron;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use \Exception;

class CronSystem
{
    /**
     * Check if ww have valid Doctrine connection instance
     *
     * @param Connection|null $conn
     * @return bool
     */
    public static function hasDbConnection(Connection $conn = null)
    {
        return ($conn instanceof Connection);
    }

    /**
     * Check if cron system is installed
     *
     * @param Connection|null $conn
     * @return bool
     */
    public static function isInstalled(Connection $conn = null)
    {
        // check if table cron exists
        try {
            $conn->query("SELECT id FROM climber_cron LIMIT 1");
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Connect
     * @param array $database_config
     * @return Connection
     */
    public static function connect(array $database_config)
    {
        return DriverManager::getConnection($database_config, new Configuration());
    }
}
