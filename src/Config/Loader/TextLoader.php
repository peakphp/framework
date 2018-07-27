<?php

declare(strict_types=1);

namespace Peak\Config\Loader;

use Peak\Config\Exception\FileNotFoundException;
use Peak\Config\Exception\LoaderException;

/**
 * Class TextLoader
 * @package Peak\Config\Loader
 */
class TextLoader implements LoaderInterface
{
    /**
     * @param mixed $resource
     * @return array|mixed
     * @throws FileNotFoundException
     * @throws LoaderException
     */
    public function load($resource)
    {
        if (!file_exists($resource)) {
            throw new FileNotFoundException($resource);
        }

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
