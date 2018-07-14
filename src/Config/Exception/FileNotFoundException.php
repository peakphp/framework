<?php

declare(strict_types=1);

namespace Peak\Config\Exception;

/**
 * Class FileNotFoundException
 * @package Peak\Config\Exception
 */
class FileNotFoundException extends \Exception
{
    /**
     * FileNotFoundException constructor.
     *
     * @param string $file
     */
    public function __construct(string $file)
    {
        parent::__construct('Config file '.$file.' not found');
    }
}
