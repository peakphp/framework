<?php

declare(strict_types=1);

namespace Peak\Config\Exception;

use Peak\Blueprint\Config\ConfigException;

class NoFileHandlersException extends \Exception implements ConfigException
{
    /**
     * NoFileHandlersException constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        parent::__construct('No support found for "'.$name.'" file type.');
    }
}
