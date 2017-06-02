<?php

namespace Peak\Config;

use Peak\Config\DotNotation;
use \Exception;

class File extends DotNotation
{
    protected $file;

    /**
     * Set array of data OR a file optionally
     *
     * @param array $vars
     */
    public function __construct($vars = null)
    {
        if (is_array($vars)) {
            parent::__construct($vars);
        } elseif (is_string($vars)) {
            $this->loadFile($vars);
        }
    }

    /**
     * Load a php file as an arrays of data
     *
     * @param string $file
     */
    public function loadFile($file)
    {
        if (pathinfo($file, PATHINFO_EXTENSION) !== 'php') {
            throw new Exception(__CLASS__.': '.basename($file).' is not a php file');
        }

        if (!file_exists($file)) {
            throw new Exception(__CLASS__.': '.$file.' not found');
        }

        $vars = include $file;

        if (!is_array($vars)) {
            throw new Exception(__CLASS__.': '.$file.' should return an array');
        }

        $this->items = $vars;
        $this->file = $file;
    }

    /**
     * Save content to php array file
     *
     * @param  string|null $file if not specified, it will take the same file used by loadFile()
     */
    public function saveToFile($file = null)
    {
        if (isset($file)) {
            $this->file = $file;
        }

        if (file_exists($this->file) && !is_writable($this->file)) {
            throw new Exception(__CLASS__.': '.$this->file.' is not writable');
        }

        file_put_contents($this->file, '<?php return ' . var_export($this->items, true) . ';');
    }
}
