<?php

declare(strict_types=1);

namespace Peak\Config\Exception;

use Peak\Blueprint\Config\ConfigException;

class CachePathNotWritableException extends \Exception implements ConfigException
{
    /**
     * @var string
     */
    private $path;

    /**
     * CachePathNotWritableException constructor.
     *
     * @param string $path
     */
    public function __construct(string $path)
    {
        parent::__construct('Config cache path "'.$path.'" not writable');
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }
}
