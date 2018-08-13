<?php

declare(strict_types=1);

namespace Peak\Config\Exception;

use Psr\SimpleCache\InvalidArgumentException;

/**
 * Class CacheInvalidKeyException
 * @package Peak\Config\Exception
 */
class CacheInvalidKeyException extends \Exception implements InvalidArgumentException
{
    /**
     * CacheInvalidKeyException constructor.
     */
    public function __construct()
    {
        parent::__construct('Invalid value for cache $key. String only');
    }
}
