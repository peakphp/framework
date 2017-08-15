<?php

namespace Peak\Climber\Cron\Exception;

use \Exception;

class DatabaseNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct('No connection to a database has been found!');
    }
}
