<?php
namespace Peak\Application;

use Peak\Exception;
use Peak\Config\DotNotation;
use Peak\Application\ConfigEnv;

/**
 */
class Config extends DotNotation
{

    /**
     * Default config
     * @var array
     */
    private $_default = [
        'ns'   => 'App',  //namespace
        'env'  => 'prod', 
        'conf' => 'config.php',
        'path' => [
            'public' => '',
            'app'    => '',
            'apptree' =>  []
        ],
    ];


    /**
     * Construct
     * 
     * @param array $config user config
     */
    public function __construct($config = null) 
    {
        $this->setVars($this->_default);

        if(isset($config)) {
            $this->merge($config); // merge default with user conf
        }

        $this->_validate();
        $this->_defineConstants();

        $conf_env = new ConfigEnv($this); // check app conf file and merge also
 
        $this->merge($conf_env->getEnvConfig());
    }

    /**
     * Generate default application tree
     *
     * @param   string $root
     * @return  array
     */
    public function defaultAppTree($root)
    {
        return [
            'application'         => $root,
            'cache'               => $root.'/cache',
            'controllers'         => $root.'/controllers',
            'controllers_helpers' => $root.'/controllers/helpers',
            'models'              => $root.'/models',
            'modules'             => $root.'/modules',
            'lang'                => $root.'/lang',
            'views'               => $root.'/views',
            'views_ini'           => $root.'/views/ini',
            'views_helpers'       => $root.'/views/helpers',
            'views_themes'        => $root.'/views',
            'theme'               => $root.'/views',
            'theme_scripts'       => $root.'/views/scripts',
            'theme_partials'      => $root.'/views/partials',
            'theme_layouts'       => $root.'/views/layouts',
            'theme_cache'         => $root.'/views/cache'
        ];
    }

    /**
     * Validate require config values
     */
    private function _validate() 
    {
        if(!$this->have('path.public')) {
            throw new Exception('ERR_CORE_INIT_CONST_MISSING', array('Public root','PUBLIC_ROOT'));
        }

        if(!$this->have('path.app'))
            throw new Exception('ERR_CORE_INIT_CONST_MISSING', array('Application root','APPLICATION_ROOT'));

        if(!$this->have('env')) {
            throw new Exception('ERR_APP_ENV_MISSING');
        }

        if(!$this->have('conf')) {
            throw new Exception('ERR_APP_CONF_MISSING');
        }
    }

    /**
     * Define important constants
     */
    private function _defineConstants()
    {
        //define server document root absolute path
        $svr_path = str_replace('\\','/',realpath($_SERVER['DOCUMENT_ROOT']));
        if(substr($svr_path, -1, 1) !== '/') $svr_path .= '/';

        define('SVR_ABSPATH',         $svr_path); 
        define('LIBRARY_ABSPATH',     realpath(__DIR__.'/../'));
        define('PUBLIC_ABSPATH',      realpath(SVR_ABSPATH . $this->get('path.public')));
        define('APPLICATION_ABSPATH', realpath(SVR_ABSPATH . $this->get('path.app')));
        define('APPLICATION_ENV',     $this->get('env'));
    }
}