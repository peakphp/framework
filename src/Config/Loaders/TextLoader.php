<?php

namespace Peak\Config\Loaders;

use Peak\Config\Exceptions\LoaderException;
use Peak\Config\LoaderInterface;

class TextLoader implements LoaderInterface
{
    /**
     * @param $resource
     * @throws LoaderException
     */
    public function load($resource)
    {
        $content = [];
        $handle = fopen($resource, 'r');

        if (!$handle) {
            throw new LoaderException(__CLASS__ . ': unable to load ' . $resource);
        }

        while (($line = fgets($handle)) !== false) {
            $content[] = trim($line);
        }

        fclose($handle);

        return $content;
    }
}
