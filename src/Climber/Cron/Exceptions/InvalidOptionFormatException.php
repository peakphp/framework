<?php

declare(strict_types=1);

namespace Peak\Climber\Cron\Exceptions;

use \Exception;

/**
 * Class InvalidOptionFormatException
 * @package Peak\Climber\Cron\Exceptions
 */
class InvalidOptionFormatException extends Exception
{
    /**
     * InvalidDatabaseConfigException constructor.
     * @param string $option_name
     */
    public function __construct(string $option_name)
    {
        parent::__construct('Invalid option format for '.trim(strip_tags($option_name)));
    }
}
