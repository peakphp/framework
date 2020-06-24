<?php

declare(strict_types=1);

namespace Peak\Common\Traits;

use Exception;
use function file_exists;
use function is_array;

trait LoadArrayFiles
{
    protected array $array_files_content = [];

    /**
     * @param array $files
     * @param string|null $basepath
     * @return array
     * @throws Exception
     */
    protected function getArrayFilesContent(array $files, string $basepath = null): array
    {
        foreach ($files as $file) {
            $file = (isset($basepath)) ? $basepath.'/'.$file : $file;
            if (!file_exists($file)) {
                throw new Exception(__CLASS__.': file "'.$file.'" not found');
            }

            $content = include $file;

            if (!is_array($content)) {
                throw new Exception(__CLASS__.': file "'.$file.'" doesn\'t return an array');
            }

            $this->array_files_content[] = $content;
        }

        return $this->array_files_content;
    }
}
