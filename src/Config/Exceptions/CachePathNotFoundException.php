<?php

declare(strict_types=1);

namespace Peak\Config\Exceptions;

class CachePathNotFoundException extends \Exception
{
    /**
     * CachePathNotFoundException constructor.
     *
     * @param string $path
     */
    public function __construct(string $path)
    {
        parent::__construct('Config cache path "'.$path.'"" not found');
    }
}
