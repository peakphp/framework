<?php

namespace Peak\Climber;

use Peak\Bedrock\Application\Config;
use Peak\Config\ConfigFactory;

class ConfigResolver
{
    /**
     * Default cli application configuration
     * @var array
     */
    protected $default = [
        'name' => 'Console Application', //default cli application name
        'version' => '1.0', //cli application version
        'ns'   => 'Cli',    //namespace for cli application
        'app_ns' => 'App',  //namespace for web application
        'env'  => 'prod',   //cli environment (dev, prod, staging, testing)
        'conf' => [],       //cli configuration file(s)
        'path' => [
            'app' => '',    //web application path
            'public' => '', //web application public pah
        ],
    ];

    /**
     * @var Config
     */
    protected $config;

    /**
     * ConfigResolver constructor.
     *
     * @param array $config
     * @throws \Peak\Config\Exception\UnknownResourceException
     */
    public function __construct(array $config = [])
    {
        // configuration
        $final = [
            $this->default,
            $config
        ];

        if (isset($config['conf']) && !empty($config['conf'])) {
            if (!is_array($config['conf'])) {
                $config['conf'] = [$config['conf']];
            }
            foreach ($config['conf'] as $conf) {
                $final[] = $conf;
            }
        }

        $configFactory = new ConfigFactory();
        $this->config = $configFactory->loadResourcesWith($final, new Config());

        if (!defined('APPLICATION_ENV')) {
            define('APPLICATION_ENV', $this->config->env);
        }
    }

    /**
     * @return Config
     */
    public function getMountedConfig()
    {
        return $this->config;
    }
}
