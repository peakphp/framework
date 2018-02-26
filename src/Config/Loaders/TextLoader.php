<?php

namespace Peak\Config\Loaders;

use Peak\Config\LoaderInterface;
use \Exception;

class TextLoader implements LoaderInterface
{
    /**
     * @param $file
     * @throws Exception
     */
    public function loadFileContent($file)
    {
        $content = [];
        $handle = fopen($file, 'r');

        if (!$handle) {
            throw new Exception(__CLASS__ . ': unable to load ' . $file);
        }

        while (($line = fgets($handle)) !== false) {
            $content[] = trim($line);
        }

        fclose($handle);

        return $content;
    }
}
