<?php

declare(strict_types=1);

namespace Peak\Climber\Cron\Exceptions;

use \Exception;

/**
 * Class TablesNotFoundException
 * @package Peak\Climber\Cron\Exceptions
 */
class TablesNotFoundException extends Exception
{
    /**
     * TablesNotFoundException constructor.
     */
    public function __construct()
    {
        parent::__construct('Cron system is not installed. Please, use command cron:install before using cron executor.');
    }
}
