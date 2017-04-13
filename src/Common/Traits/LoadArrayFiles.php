<?php

namespace Peak\Common\Traits;

use \Exception;

trait LoadArrayFiles
{
    /**
     * The content of php array files
     * @var array
     */
    protected $array_files_content = [];

    /**
     * Load php array file(s)
     *
     * @param  array $files Files to load as array
     * @return array
     */
    protected function getArrayFilesContent($files, $basepath = null)
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
