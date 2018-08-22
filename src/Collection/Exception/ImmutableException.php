<?php

declare(strict_types=1);

namespace Peak\Collection\Exception;

/**
 * Class ImmutableException
 * @package Peak\Collection\Exception
 */
class ImmutableException extends \Exception
{
    /**
     * ImmutableException constructor.
     *
     * @param string $type
     */
    public function __construct(string $type)
    {
        parent::__construct('properties of immutable object cannot be '.$type);
    }
}
