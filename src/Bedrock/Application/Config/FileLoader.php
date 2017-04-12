<?php

namespace Peak\Bedrock\Application\Config;

use \Exception;

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
        $extension = pathinfo($file, PATHINFO_EXTENSION);

        //load configuration object according to the file extension
        switch ($extension) {

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
                throw new Exception('Application configuration file format "'.$extension.'" is not supported');
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
