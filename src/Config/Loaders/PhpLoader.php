<?php

namespace Peak\Config\Loaders;

use Peak\Config\LoaderInterface;

class PhpLoader implements LoaderInterface
{
    public function loadFileContent($file)
    {
        return include $file;
    }
}
