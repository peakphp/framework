<?php

declare(strict_types=1);

namespace Peak\Common\Pipeline\Exception;

/**
 * Class MissingPipeInterfaceException
 * @package Peak\Common\Pipeline\Exception
 */
class MissingPipeInterfaceException extends \Exception
{
    /**
     * MissingPipeInterfaceException constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        parent::__construct('Pipe "'.$name.'" must implements Peak\Pipelines\PipeInterface');
    }
}
