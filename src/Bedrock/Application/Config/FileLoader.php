<?php

namespace Peak\BedRock\Application\Config;

use Peak\Exception;

class FileLoader
{
    /**
     * Configuration
     * @var object
     */
    protected $config;

    /**
     * Constructor
     *
     * @param  string $file
     */
    public function __construct($file)
    {
        $this->loadConfigFile($file);
    }

    /**
     * Load application config file
     *
     * @param  string $file
     */
    protected function loadConfigFile($file)
    {
        //load configuration object according to the file extension
        switch (pathinfo($file, PATHINFO_EXTENSION)) {

            case 'php':
                $conf = new \Peak\Config\File($file);
                break;
            case 'ini':
                $conf = new \Peak\Config\File\Ini($file, true);
                break;
            case 'json':
                $conf = new \Peak\Config\File\Json($file, true);
                break;
            default:
                throw new Exception('ERR_CONFIG_FILE');
                break;
        }

        $this->config = $conf;
    }

    /**
     * Get the loaded config object
     *
     * @return object
     */
    public function getConfig()
    {
        return $this->config;
    }
}
