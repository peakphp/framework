<?php

declare(strict_types=1);

namespace Peak\Config\Exception;

class CachePathNotFoundException extends \Exception
{
    /**
     * @var string
     */
    private $path;

    /**
     * CachePathNotFoundException constructor.
     *
     * @param string $path
     */
    public function __construct(string $path)
    {
        parent::__construct('Config cache path "'.$path.'" not found');
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
