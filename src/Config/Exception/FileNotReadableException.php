<?php

declare(strict_types=1);

namespace Peak\Config\Exception;

/**
 * Class FileNotReadableException
 * @package Peak\Config\Exception
 */
class FileNotReadableException extends \Exception
{
    /**
     * FileNotReadableException constructor.
     *
     * @param string $file
     */
    public function __construct(string $file)
    {
        parent::__construct('Config file '.$file.' is not readable');
    }
}
