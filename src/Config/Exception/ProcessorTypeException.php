<?php

declare(strict_types=1);

namespace Peak\Config\Exception;

class ProcessorTypeException extends ProcessorException
{
    /**
     * ProcessorTypeException constructor.
     * @param string $who
     * @param string $expect
     * @param mixed $given
     */
    public function __construct(string $who, string $expect, $given)
    {
        parent::__construct($who.' expects data to be of type '.$expect.'. '.gettype($given).' given.');
    }
}
