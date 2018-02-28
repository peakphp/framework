<?php

namespace Peak\Config\Loaders;

use Peak\Config\LoaderInterface;
use \Exception;

class TextLoader implements LoaderInterface
{
    /**
     * @param $resource
     * @throws Exception
     */
    public function load($resource)
    {
        $content = [];
        $handle = fopen($resource, 'r');

        if (!$handle) {
            throw new Exception(__CLASS__ . ': unable to load ' . $resource);
        }

        while (($line = fgets($handle)) !== false) {
            $content[] = trim($line);
        }

        fclose($handle);

        return $content;
    }
}
