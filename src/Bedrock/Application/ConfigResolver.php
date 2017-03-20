<?php

namespace Peak\Bedrock\Application;

use Peak\Bedrock\Application\Config;
use Peak\Bedrock\Application\Config\FileLoader;
use Peak\Bedrock\Application\Config\Environment;
use Peak\Common\DataException;

class ConfigResolver
{
    /**
     * Default config
     * @var array
     */
    private $default = [
        'ns'   => 'App',        //namespace
        'env'  => 'prod',       //app environment (dev,prod,staging,testing)
        'conf' => 'config.php', //app config file relative to path.app config
        'name' => 'peakapp',    //default application name
        'path' => [             //paths
            'public'  => '',
            'app'     => '',
            'apptree' =>  []
        ],
    ];

    /**
     * The final app config collection
     * @var object
     */
    protected $app_config;

    /**
     * Construct
     *
     * @param array $config user config
     */
    public function __construct($config = [])
    {
        $this->app_config = new Config($this->default);
        $this->app_config->mergeRecursiveDistinct($config);

        $this->validate(); // validate user conf
        $this->defineConstants(); // define default app constants

        //print_r($this->app_config);

        $config_loader = new FileLoader($this->getConfigFilepath());

        $config_env = new Environment(
            $config_loader->getConfig(),
            $this->app_config
        );

        $this->app_config->mergeRecursiveDistinct($config_env->getEnvConfig());
    }

    /**
     * Get app config
     *
     * @return object
     */
    public function getMountedConfig()
    {
        return $this->app_config;
    }

    /**
     * Get application config filepath
     *
     * @return string
     */
    private function getConfigFilepath()
    {
        $file = $this->app_config->get('path.app').'/'.$this->app_config->get('conf');
        $realpath = realpath($file);

        if ($realpath === false) {
            throw new DataException('Application configuration file not found', $file);
        }

        $path = str_replace('\\', '/', $realpath);            

        return $path;
    }

    /**
     * Define important constants
     */
    private function defineConstants()
    {
        //define server document root absolute path
        $svr_path = str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT']));
        if (substr($svr_path, -1, 1) !== '/') {
            $svr_path .= '/';
        }

        define('SVR_ABSPATH', $svr_path);
        define('LIBRARY_ABSPATH', realpath(__DIR__.'/../'));
        define('PUBLIC_ABSPATH', realpath($this->app_config->get('path.public')));
        define('APPLICATION_ABSPATH', realpath($this->app_config->get('path.app')));
        define('APPLICATION_ENV', $this->app_config->get('env'));
    }

    /**
     * Validate require config values
     */
    private function validate()
    {
        if (!file_exists($this->app_config->get('path.public'))) {
            throw new DataException('Public path not found', $this->app_config->get('path.public'));
        }

        if (!file_exists($this->app_config->get('path.app'))) {
            throw new DataException('Application path not found', $this->app_config->get('path.app'));
        }
    }
}
