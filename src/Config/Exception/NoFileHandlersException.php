<?php

declare(strict_types=1);

namespace Peak\Config\Exception;

/**
 * Class NoFileHandlersException
 * @package Peak\Config\Exception
 */
class NoFileHandlersException extends \Exception
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
