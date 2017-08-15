<?php

namespace Peak\Climber\Cron;

use Doctrine\DBAL\Connection;
use \Exception;

class Cron
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
}
