<?php

namespace Peak\Climber\Cron\Exceptions;

use \Exception;

class InvalidOptionFormatException extends Exception
{
    /**
     * InvalidDatabaseConfigException constructor.
     * @param string $option_name
     */
    public function __construct($option_name)
    {
        parent::__construct('Invalid option format for '.trim(strip_tags($option_name)));
    }
}
