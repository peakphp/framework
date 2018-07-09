<?php

declare(strict_types=1);

namespace Peak\Config\Loader;

/**
 * Interface LoaderInterface
 * @package Peak\Config\Loader
 */
interface LoaderInterface
{
    /**
     * @param mixed $resource
     * @return mixed
     */
    public function load($resource);
}
