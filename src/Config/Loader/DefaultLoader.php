<?php

declare(strict_types=1);

namespace Peak\Config\Loader;

use Peak\Blueprint\Common\ResourceLoader;
use Peak\Config\Exception\FileNotFoundException;

/**
 * Class DefaultLoader
 * @package Peak\Config\Loader
 */
class DefaultLoader implements ResourceLoader
{
    /**
     * @param mixed $resource
     * @return bool|mixed|string
     * @throws FileNotFoundException
     */
    public function load($resource)
    {
        if (!file_exists($resource)) {
            throw new FileNotFoundException($resource);
        }
        return file_get_contents($resource);
    }
}
