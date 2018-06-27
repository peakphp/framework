<?php

declare(strict_types=1);

namespace Peak\Config\Exception;

class CachePathNotWritableException extends \Exception
{
    /**
     * CachePathNotWritableException constructor.
     *
     * @param string $path
     */
    public function __construct(string $path)
    {
        parent::__construct('Config cache path "'.$path.'"" not writable');
    }
}
