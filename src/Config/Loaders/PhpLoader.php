<?php

namespace Peak\Config\Loaders;

use Peak\Config\LoaderInterface;

class PhpLoader implements LoaderInterface
{
    /**
     * @param $resource
     * @return mixed
     */
    public function load($resource)
    {
        return include $resource;
    }
}
