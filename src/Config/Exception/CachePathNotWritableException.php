<?php

declare(strict_types=1);

namespace Peak\Config\Exception;

class CachePathNotWritableException extends \Exception
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
