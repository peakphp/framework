<?php

declare(strict_types=1);

namespace Peak\View\Exception;

class FileNotFoundException extends \Exception
{
    /**
     * FileNotFoundException constructor.
     *
     * @param string $file
     */
    public function __construct(string $file)
    {
        parent::__construct('View file '.$file.' not found');
    }
}
