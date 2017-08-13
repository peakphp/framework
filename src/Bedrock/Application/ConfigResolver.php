<?php

namespace Peak\Bedrock\Application;

use Peak\Bedrock\Application\Config\AppTree;
use Peak\Common\DataException;
use \Exception;

class ConfigResolver
{
    /**
     * Default config
     * @var array
     */
    private $default = [
        'ns' => 'App',          //namespace
        'env' => 'prod',        //app environment (dev,prod,staging,testing)
        'soft_conf' => false,   //indicate we should use soft loader for configs
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
     * Construct
     *
     * @param array $config user config
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

        $loader = 'Peak\Config\ConfigLoader';
        if (isset($config['soft_conf']) && $config['soft_conf'] === true) {
            $loader = 'Peak\Config\ConfigSoftLoader';
        }

        // build and store final application config
        $this->app_config = new Config(
            (new $loader($final))->asArray()
        );
    }

    /**
     * Get app configuration
     *
     * @return Config
     */
    public function getMountedConfig()
    {
        return $this->app_config;
    }

    /**
     * Validate require config values
     *
     * @param array $config
     */
    private function validate($config)
    {
        if (!isset($config['env'])) {
            throw new Exception('Your application doesn\'t have environment configuration');
        }

        if (!isset($config['path']['public'])) {
            throw new Exception('Your application doesn\'t have a public path configuration');
        }

        if(!file_exists($config['path']['public'])) {
            throw new DataException('Public path not found', $config['path']['public']);
        }

        if (!isset($config['path']['app'])) {
            throw new Exception('Your application doesn\'t have a path configuration');
        }
        if (!file_exists($config['path']['app'])) {
            throw new DataException('Application path not found', $config['path']['app']);
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
        $svr_path = str_replace('\\', '/', realpath(filter_var(getenv('DOCUMENT_ROOT'))));
        if (substr($svr_path, -1, 1) !== '/') {
            $svr_path .= '/';
        }

        define('SVR_ABSPATH', $svr_path);
        define('LIBRARY_ABSPATH', realpath(__DIR__.'/../'));
        define('PUBLIC_ABSPATH', realpath($config['path']['public']));
        define('APPLICATION_ABSPATH', realpath($config['path']['app']));
        define('APPLICATION_ENV', $config['env']);
    }
}
