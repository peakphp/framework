<?php

declare(strict_types=1);

namespace Peak\Config\Loader;

use Peak\Blueprint\Common\ResourceLoader;
use Peak\Config\Exception\FileNotFoundException;

use function file_exists;

/**
 * Class PhpLoader
 * @package Peak\Config\Loader
 */
class PhpLoader implements ResourceLoader
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
