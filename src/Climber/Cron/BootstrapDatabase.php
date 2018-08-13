<?php

declare(strict_types=1);

namespace Peak\Climber\Cron;

use Peak\Climber\Application;

/**
 * Class BootstrapDatabase
 * @package Peak\Climber\Cron
 */
class BootstrapDatabase
{
    /**
     * BootstrapDatabase constructor.
     * @param array $database_config
     */
    public function __construct(array $database_config)
    {
        Application::container()->add(
            CronSystem::connect($database_config),
            'CronDbConnection'
        );
    }
}
