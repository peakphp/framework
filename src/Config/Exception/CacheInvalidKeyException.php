<?php

declare(strict_types=1);

namespace Peak\Config\Exception;

use Psr\SimpleCache\InvalidArgumentException;

class CacheInvalidKeyException extends \Exception implements InvalidArgumentException
{
    /**
     * InvalidCacheKeyException constructor.
     */
    public function __construct()
    {
        parent::__construct('Invalid value for cache $key. String only');
    }
}
