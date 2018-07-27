<?php

declare(strict_types=1);

namespace Peak\Bedrock\Application;

use Peak\Bedrock\Application\Config as AppConfig;
use Peak\Bedrock\Application\Config\AppTree;
use Peak\Bedrock\Application\Exceptions\MissingConfigException;
use Peak\Bedrock\Application\Exceptions\PathNotFoundException;
use Peak\Config\ConfigFactory;
use Peak\Config\ConfigInterface;

/**
 * Class ConfigResolver
 * @package Peak\Bedrock\Application
 */
class ConfigResolver
{
    /**
     * Default config
     * @var array
     */
    private $default = [
        'ns' => 'App',          //namespace
        'env' => 'prod',        //app environment (dev,prod,staging,testing)
        'conf' => [],           //config(s) file(s)
        'name' => 'app',        //default application name
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
     * ConfigResolver constructor.
     *
     * @param array $config
     * @throws MissingConfigException
     * @throws PathNotFoundException
     * @throws \Peak\Config\Exception\UnknownResourceException
     */
    public function __construct($config = [])
    {
        // validate user conf
        $this->validate($config);

        // define default app constants
        $this->defineConstants($config);

        // get application path tree
        $config['path']['apptree'] = (new AppTree(APPLICATION_ABSPATH))->get();

        // prepare the final app configuration
        $final = [
            $this->default,
            $config
        ];

        // load external app config
        if (isset($config['conf'])) {
            if (is_string($config['conf'])) {
                $final[] = $config['conf'];
            } elseif (is_array($config['conf'])) {
                foreach ($config['conf'] as $conf) {
                    $final[] = $conf;
                }
            }
        }

        $configFactory = new ConfigFactory();
        $this->app_config = $configFactory->loadResourcesWith($final, new AppConfig());
    }

    /**
     * Get app configuration
     *
     * @return ConfigInterface
     */
    public function getMountedConfig()
    {
        return $this->app_config;
    }

    /**
     * Validate require config values
     *
     * @param array $config
     * @throws MissingConfigException
     * @throws PathNotFoundException
     */
    private function validate($config)
    {
        if (!isset($config['env'])) {
            throw new MissingConfigException('env');
        }

        if (!isset($config['path']['public'])) {
            throw new MissingConfigException('path.public');
        }

        if (!file_exists($config['path']['public'])) {
            throw new PathNotFoundException($config['path']['public'], 'Public path');
        }

        if (!isset($config['path']['app'])) {
            throw new MissingConfigException('path.app');
        }
        if (!file_exists($config['path']['app'])) {
            throw new PathNotFoundException($config['path']['app'], 'Application path');
        }
    }

    /**
     * Define important constants
     *
     * @param array $config
     */
    private function defineConstants($config)
    {
        //define server document root absolute path
        $doc_path = str_replace('\\', '/', realpath(filter_var(getenv('DOCUMENT_ROOT'))));
        if (substr($doc_path, -1, 1) !== '/') {
            $doc_path .= '/';
        }

        define('ROOT_ABSPATH', $doc_path);
        define('PUBLIC_ABSPATH', realpath($config['path']['public']));
        define('APPLICATION_ABSPATH', realpath($config['path']['app']));
        define('APPLICATION_ENV', $config['env']);
    }
}
