<?php
namespace Peak\Application;

use Peak\Registry;
use Peak\Application\Config;
use Peak\Exception;

class ConfigEnv
{

    protected $app_config;
    protected $env_config;

    public function __construct(Config $app_config)
    {
        $this->app_config = $app_config;
        $this->_processEnv();
    }

    public function getEnvConfig()
    {
        return $this->env_config;
    }

    private function _processEnv()
    {
        $env      = $this->app_config->env;

        //echo $this->app_config->conf;
        //$filepath = $this->app_config->filepath;
        $apppath  = $this->app_config->path['app'];

        $conf = $this->_loadConfigFile();

        // validate application config
        $this->_validate($conf);

        $conf->set(
            'path.apptree', 
            $this->app_config->defaultAppTree($this->app_config->get('path.app'))
        );
 
        
        //add APPLICATION_ABSPATH to path config array if exists
        // if(isset($conf->all['path'])) {
        //     foreach($conf->all['path'] as $pathname => $path) {
        //         $conf->all['path'][$pathname] = $apppath.'/'.$path;
        //     }
        // }
        
        //add APPLICATION_ABSPATH to 'path' key value
        // if(is_array($conf->$env) && (array_key_exists('path', $conf->$env))) {
        //     $conf_env = &$conf->$env;
        //     foreach($conf_env['path'] as $pathname => $path) {
        //         $conf_env['path'][$pathname] = $apppath.'/'.$path;
        //     }
        // }
        
        
        //merge app config paths with core app paths    
        if(isset($conf->all['path'])) {
            //$conf->all['path'] = $conf->arrayMergeRecursive($this->app_config->defaultAppTree($apppath), $conf->all['path']);
        }
        else {
            //fix a notice in case [all] section doesn't exists, we create it
            if(!isset($conf->all)) $conf->all = array();
            $conf->all['path'] = $this->app_config->defaultAppTree($apppath);
        }

        //try to merge array section 'all' with current environment section if exists
        if(isset($conf->all) && isset($conf->$env)) {
            $conf->setVars($conf->arrayMergeRecursive($conf->all,$conf->$env));
        }
        elseif(isset($conf->$env)) $conf->setVars($conf->$env);
        else $conf->setVars($conf->all);

        //save transformed config to registry
        //Registry::set('config', $conf);         

        //set some php ini settings
        if(isset($conf->php)) {
            $this->_processPHPconfig($conf->php);
        }

        // final environement settings
        $this->env_config = $conf->getVars();
    }


    /**
     * Validation application config
     * 
     * @param  object $conf
     */
    private function _validate($conf)
    {
        // default env aka all
        if(!$conf->have('all')) {
            throw new Exception(
                'ERR_CUSTOM', 
                'Your application doesn\'t have default "all" configuration'
            );
        }

        // current env
        if(!$conf->have($this->app_config->env)) {
            throw new Exception(
                'ERR_CUSTOM', 
                'Your application doesn\'t have "'.$this->app_config->env.'" configuration'
            );
        }
    }

    /**
     * Get application config filepath
     * 
     * @return string
     */
    private function _getAppConfigFilepath()
    {
        return str_replace('\\', '/', 
            realpath(SVR_ABSPATH.'/'.$this->app_config->get('path.app').'/'.$this->app_config->get('conf'))
        );
    }

    /**
     * Load application config file object
     *
     * @return object
     */
    private function _loadConfigFile()
    {
        $file = $this->_getAppConfigFilepath();
    
        //load configuration object according to the file extension
        switch(pathinfo($file, PATHINFO_EXTENSION)) {

            case 'php' :
                $conf = new \Peak\Config\File($file);
                break;
            
            case 'ini' :
                $conf = new \Peak\Config\File\Ini($file, true);
                break;

            case 'json' :
                $conf = new \Peak\Config\File\Json($file, true);
                break;

            default :
                throw new Exception('ERR_CONFIG_FILE');
                break;
        }

        return $conf;
    }

    /**
     * Process php config
     */
    private function _processPHPconfig($php)
    {
        foreach($php as $setting => $val) {
            if(!is_array($val)) ini_set($setting, $val);
            else {
                foreach($val as $k => $v) ini_set($setting.'.'.$k, $v);
            }               
        }
    }
}