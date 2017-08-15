<?php

namespace Peak\Climber\Cron\Exception;

use \Exception;

class InvalidDatabaseConfigException extends Exception
{
    public function __construct()
    {
        parent::__construct('Configuration [cron.db] is missing or invalid.');
    }
}