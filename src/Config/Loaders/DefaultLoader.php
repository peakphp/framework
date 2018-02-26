<?php

namespace Peak\Config\Loaders;

use Peak\Config\LoaderInterface;

class DefaultLoader implements LoaderInterface
{
    /**
     * @param $file
     * @return bool|string
     */
    public function loadFileContent($file)
    {
        return file_get_contents($file);
    }
}
