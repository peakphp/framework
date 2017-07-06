<?php

namespace Peak\Climber\Cron;

use Doctrine\DBAL\Connection;
use \Exception;

class InstallDatabase
{
    /**
     * Constructor
     *
     * @param Connection $conn
     * @param string $driver
     * @throws Exception
     */
    public function __construct(Connection $conn, $driver)
    {
        $install_script = __DIR__.'/install.'.$driver.'.sql';

        if (!file_exists($install_script)) {
            throw new Exception(__CLASS__.': database driver ['.$driver.'] not supported.');
        }

        $content = explode(';', file_get_contents($install_script));
        foreach ($content as $query) {
            $query = trim($query);
            if (empty($query)) {
                continue;
            }
            $conn->exec($query);
        }
    }
}
