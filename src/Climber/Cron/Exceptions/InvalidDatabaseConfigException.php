<?php

declare(strict_types=1);

namespace Peak\Climber\Cron\Exceptions;

use \Exception;

/**
 * Class InvalidDatabaseConfigException
 * @package Peak\Climber\Cron\Exceptions
 */
class InvalidDatabaseConfigException extends Exception
{
    /**
     * InvalidDatabaseConfigException constructor.
     */
    public function __construct()
    {
        parent::__construct('Configuration [cron.db] is missing or invalid.');
    }
}
