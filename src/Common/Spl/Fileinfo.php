<?php

namespace Peak\Common\Spl;

/**
 * Extension of class SplFileInfo. Add auto/custom formatting options.
 */
class Fileinfo extends SplFileInfo
{
    /**
     * Change these setting to create custom formatting
     * @var array
     */
    public $formats = [
        'time'  => null,
        'size'  => false,
        'perms' => false
    ];

    /**
     * Specify a filepath to use and add custom format
     *
     * @param string $filepath
     * @param array  $format
     */
    public function __construct($filepath, $formats = null)
    {
        parent::__construct($filepath);
        if (is_array($formats)) {
            $this->formats = array_merge($this->formats, $formats);
        }
    }

    /**
     * Change the current file used
     *
     * @param string $filepath
     * @param array  $format
     */
    public function setFile($filepath, $formats = null)
    {
        $this->__construct($filepath, $formats);
    }

    /**
     * Check if format exists
     *
     * @param  string $name
     * @return misc
     */
    private function getFormat($name)
    {
        if (!is_null($this->formats[$name])) {
            return $this->formats[$name];
        }
        return false;
    }

    /**
     * Get file perms. use $format['perms']
     *
     * @param  bool $format will format perms to 0XXX
     * @return string
     */
    public function getPerms($format = false)
    {
        $perms = parent::getPerms();
        if (($format) || ($this->getFormat('perms'))) {
            $perms = substr(sprintf('%o', $perms), -4);
        }
        return $perms;
    }

    /**
     * Get latest file access time. use $format['time']
     *
     * @param  string $format
     * @return string
     */
    public function getAtime($format = null)
    {
        $time = parent::getATime();
        return $this->formatTime($time, $format);
    }

    /**
     * Get file creation time. use $format['time']
     *
     * @param  string $format
     * @return string
     */
    public function getCtime($format = null)
    {
        $time = parent::getCTime();
        return $this->formatTime($time, $format);
    }

    /**
     * Get file modification time. use $format['time']
     *
     * @param  string $format
     * @return string
     */
    public function getMtime($format = null)
    {
        $time = parent::getMTime();
        return $this->formatTime($time, $format);
    }

    /**
     * Format time
     *
     * @param  string $time
     * @param  string $format
     * @return string
     */
    protected function formatTime($time, $format = null)
    {
        if (!isset($format)) {
            $format = $this->getFormat('time');
        }
        return date($format, $time);
    }

    /**
     * Get file size. use $format['size']
     *
     * @param  bool $format
     * @return integer/string
     */
    public function getSize($format = false)
    {
        if (!$format) {
            return parent::getSize();
        }
        
        $unit = ['B','kB','MB','GB','TB','PB'];
        return @round($this->_size/pow(1024, ($i=floor(log($this->_size, 1024)))), 2).' '.$unit[$i];
    }

    /**
     * Get file extension
     *
     * @return string
     */
    public function getExtension()
    {
        return pathinfo($this->getFilename(), PATHINFO_EXTENSION);
    }
}
