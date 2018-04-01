<?php

namespace Peak\Config\Loaders;

use Peak\Config\LoaderInterface;

class DefaultLoader implements LoaderInterface
{
    /**
     * @param $resource
     * @return bool|string
     */
    public function load($resource)
    {
        return file_get_contents($resource);
    }
}
