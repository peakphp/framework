<?php

declare(strict_types=1);

namespace Peak\Di\Exception;

use \Exception;

/**
 * Class AmbiguousResolutionException
 * @package Peak\Di\Exception
 */
class AmbiguousResolutionException extends Exception
{
    /**
     * AmbiguousResolutionException constructor.
     *
     * @param string $interface
     * @param array $instance
     */
    public function __construct(string $interface, array $instance)
    {
        parent::__construct('Dependencies for interface '.$interface.' is ambiguous. There is '.count($instance).' different stored instances for this interface.');
    }
}

