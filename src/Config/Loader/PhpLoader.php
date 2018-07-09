<?php

declare(strict_types=1);

namespace Peak\Config\Loader;

use Peak\Config\Exception\FileNotFoundException;

/**
 * Class PhpLoader
 * @package Peak\Config\Loader
 */
class PhpLoader implements LoaderInterface
{
    /**
     * @param mixed $resource
     * @return mixed
     * @throws FileNotFoundException
     */
    public function load($resource)
    {
        if (!file_exists($resource)) {
            throw new FileNotFoundException($resource);
        }
        return include $resource;
    }
}
