<?php
namespace Peak\Config;

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
        if(is_array($vars)) $this->setVars($vars);
        elseif(is_string($vars)) $this->loadFile($vars);
    }

    /**
     * Load a php file as an arrays of data
     *
     * @param string $file
     */
    public function loadFile($file)
    {
        if(pathinfo($file, PATHINFO_EXTENSION) === 'php' && file_exists($file)) {
            $vars = include $file;
            $this->setVars($vars);
        }
    }
}