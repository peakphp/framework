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

        // we silence error(s) so we can catch them and throw a proper exception after
        $handle = @fopen($resource, 'r');

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
