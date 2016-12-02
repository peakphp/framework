<?php
namespace Peak\Application;

use Peak\Exception;
use Peak\Core;
use Peak\Config as BaseConfig;
use Peak\Application\ConfigEnv;

/**
 */
class Config extends BaseConfig
{

    private $_default = [
        'env'  => 'prod',
        'ns'   => 'App\\',
        'conf' => '',
        'path' => [
            'public' => '',
            'app'    => '',
            'apptree' =>  []
        ],
    ];

    /**
     * Generate default application tree
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
     * Construct
     * 
     * @param array $config user config
     */
    public function __construct($config = null) 
    {
        if(isset($config)) {
            $this->setVars(
                $this->_validate($config)
            );
        }

        $this->_defineConstants();

        $confEnv = new ConfigEnv($this);

        
        $this->merge($confEnv->getEnvConfig());
        //print_r($this->_vars);
        

    }

    /**
     * Validate current config
     * 
     * @param  [type] $config [description]
     * @return [type]         [description]
     */
    private function _validate($config) 
    {
        $config = BaseConfig::arrayMergeRecursive($this->_default, $config);
        

        if(!isset($config['path']['public'])) {
            throw new Exception('ERR_CORE_INIT_CONST_MISSING', array('Public root','PUBLIC_ROOT'));
        }

        if(!isset($config['path']['app']))
            throw new Exception('ERR_CORE_INIT_CONST_MISSING', array('Application root','APPLICATION_ROOT'));

        if(!isset($config['env'])) {
            throw new Exception('ERR_APP_ENV_MISSING');
        }

        if(!isset($config['conf'])) {
            throw new Exception('ERR_APP_CONF_MISSING');
        }
        
        //Core::initConfig($config['conf'], $config['path']['app']);
        return $config;
    }

    /**
     * [_defineConstants description]
     * @return [type] [description]
     */
    private function _defineConstants()
    {
        //define server document root absolute path
        $svr_path = str_replace('\\','/',realpath($_SERVER['DOCUMENT_ROOT']));
        if(substr($svr_path, -1, 1) !== '/') $svr_path .= '/';

        define('SVR_ABSPATH', $svr_path); 
        define('LIBRARY_ABSPATH', realpath(__DIR__.'/../'));
        define('PUBLIC_ABSPATH', realpath(SVR_ABSPATH . $this->path['public']));
        define('APPLICATION_ABSPATH', realpath(SVR_ABSPATH . $this->path['app']));
    }
}