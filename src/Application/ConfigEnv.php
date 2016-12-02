<?php
namespace Peak\Application;

use Peak\Registry;
use Peak\Application\Config;
use Peak\Config as BaseConfig;

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
        $filepath = $this->app_config->filepath;
        $apppath  = $this->app_config->path['app'];

        $filetype = pathinfo($filepath, PATHINFO_EXTENSION);
        
        //we try to solve possible problem(s) here only in dev mode instead of possibly raise an exception
        if($env === 'development') {
            $generic_filepath = LIBRARY_ABSPATH.'/Application/genericapp.ini';

            if($filetype !== 'genericapp') {
                //non-existing file
                if(!file_exists($filepath)) {
                    $filepath = $generic_filepath;
                    $filetype = 'genericapp';
                }
                //unsupported type
                else if(!in_array($filetype, array('php','ini', 'json', 'genericapp'))) {
                    $filepath = $generic_filepath;
                    $filetype = 'genericapp';
                }
            }
            else $filepath = $generic_filepath;
        }

        //load configuration object according to the file extension
        switch($filetype) {

            case 'php' :
                $conf = new BaseConfig($filepath);
                break;
            
            case 'ini' :
                if($env === 'development') {
                    //if it fail, we will use genericapp.ini in dev mode only
                    try { $conf = new BaseConfig\Ini($filepath, true);  }
                    catch(Exception $e) { $conf = new BaseConfig\Ini($generic_filepath, true);  }
                }
                else $conf = new BaseConfig\Ini($filepath, true);
                break;

            case 'json' :
                $conf = new BaseConfig\Json($filepath, true);
                break;

            case 'genericapp' :
                if($env === 'development') {
                    $conf = new BaseConfig\Ini($filepath, true);
                    break;
                } //we don't break here if we are not in dev mode               
            
            default :
                throw new Exception('ERR_CONFIG_FILE');
                break;
        }
        
        
        //check if we got the configuration for current environment mode or at least section 'all'
        if((!isset($conf->$env)) && (!isset($conf->all))) {
            if($env !== 'development') {
                throw new Exception('ERR_CUSTOM', 'no general configurations and/or '.$env.' configurations');
            }
            //here we will use Peak/Application/genericapp.ini as temporary config for the lazy user when in DEVELOPMENT ENV
            //This allow to boot an app with an empty config file
            else $conf = new BaseConfig\Ini($generic_filepath, true);           
        }
        
        //add APPLICATION_ABSPATH to path config array if exists
        if(isset($conf->all['path'])) {
            foreach($conf->all['path'] as $pathname => $path) {
                $conf->all['path'][$pathname] = $apppath.'/'.$path;
            }
        }
        
        //add APPLICATION_ABSPATH to 'path' key value
        if(is_array($conf->$env) && (array_key_exists('path', $conf->$env))) {
            $conf_env = &$conf->$env;
            foreach($conf_env['path'] as $pathname => $path) {
                $conf_env['path'][$pathname] = $apppath.'/'.$path;
            }
        }
        
        
        //merge app config paths with core app paths    
        if(isset($conf->all['path'])) {
            $conf->all['path'] = $conf->arrayMergeRecursive($this->app_config->defaultAppTree($apppath), $conf->all['path']);
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
            foreach($conf->php as $setting => $val) {
                if(!is_array($val)) ini_set($setting, $val);
                else {
                    foreach($val as $k => $v) ini_set($setting.'.'.$k, $v);
                }               
            }
        }

        $this->env_config = $conf->getVars();
    }
}