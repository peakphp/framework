<?php

declare(strict_types=1);

namespace Peak\Config\Exception;

class FileNotFoundException extends \Exception
{
    /**
     * UnknownTypeException constructor.
     *
     * @param string $file
     */
    public function __construct(string $file)
    {
        parent::__construct('Config file '.$file.' not found');
    }
}
