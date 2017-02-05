<?php
namespace Peak\Config;

use Peak\Exception;
use Peak\Config\DotNotation;

class File extends DotNotation
{

    /**
     * Set array of data OR a file optionnaly
     *
     * @param array $vars
     */
    public function __construct($vars = null)
    {
        if(is_array($vars)) $this->items = $vars;
        elseif(is_string($vars)) $this->loadFile($vars);
    }

    /**
     * Load a php file as an arrays of data
     *
     * @param string $file
     */
    public function loadFile($file)
    {
        if(pathinfo($file, PATHINFO_EXTENSION) !== 'php') {
            throw new Exception(basename($file).' is not a php file');
        }

        if(!file_exists($file)) {
            throw new Exception($file.' not found');
        }

        $vars = include $file;

        if(!is_array($vars)) {
            throw new Exception($file.' should return an array');
        }

        $this->items = $vars;

    }
}