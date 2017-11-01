<?php

namespace Peak\Climber\Cron\Exceptions;

use \Exception;

class TablesNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct('Cron system is not installed. Please, use command cron:install before using cron executor.');
    }
}
