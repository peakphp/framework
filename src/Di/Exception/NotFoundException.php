<?php

declare(strict_types=1);

namespace Peak\Di\Exception;

use Psr\Container\NotFoundExceptionInterface;
use \Exception;

/**
 * Class NotFoundException
 * @package Peak\Di\Exception
 */
class NotFoundException extends Exception implements NotFoundExceptionInterface
{
    /**
     * NotFoundException constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        parent::__construct('Could not find ['.$name.'] in the container');
    }
}
