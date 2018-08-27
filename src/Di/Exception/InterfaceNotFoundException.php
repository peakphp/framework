<?php

declare(strict_types=1);

namespace Peak\Di\Exception;

use Psr\Container\NotFoundExceptionInterface;
use \Exception;

/**
 * Class InterfaceNotFoundException
 * @package Peak\Di\Exception
 */
class InterfaceNotFoundException extends Exception implements NotFoundExceptionInterface
{
    /**
     * InterfaceNotFoundException constructor.
     *
     * @param string $interface
     */
    public function __construct(string $interface)
    {
        parent::__construct('Could not find an instance that implement interface'.$interface);
    }
}
