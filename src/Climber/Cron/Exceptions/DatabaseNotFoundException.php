<?php

declare(strict_types=1);

namespace Peak\Climber\Cron\Exceptions;

use \Exception;

/**
 * Class DatabaseNotFoundException
 * @package Peak\Climber\Cron\Exceptions
 */
class DatabaseNotFoundException extends Exception
{
    /**
     * DatabaseNotFoundException constructor.
     */
    public function __construct()
    {
        parent::__construct('No connection to a database has been found!');
    }
}
