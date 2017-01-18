<?php
namespace Peak\Application\Config;

use Peak\Exception;
use Peak\Collection;
use Peak\Application\Config\AppTree;

class Environment
{

    protected $file_config;
    protected $app_config;
    protected $env_config;

    /**
     * Constructor
     * 
     * @param Collection $file_config
     * @param Collection $app_config
     */
    public function __construct(Collection $file_config, Collection $app_config)
    {
        $this->app_config  = $app_config;
        $this->file_config = $file_config;
        $this->_processEnv();
    }

    /**
     * Get the final app config
     * 
     * @return Collection
     */
    public function getEnvConfig()
    {
        return $this->env_config;
    }

    /**
     * Process app file config with current environment
     * @return [type] [description]
     */
    private function _processEnv()
    {
        $env     = $this->app_config->env;
        $apptree = new AppTree(APPLICATION_ABSPATH);

        $this->app_config->set(
            'path.apptree', 
            $apptree->tree
        );
 
        //merge app config paths with core app paths    
        if(isset($this->file_config->all['path'])) {

            $this->file_config->all['path'] = $this->file_config->mergeRecursiveDistinct(
                $this->app_config->path, 
                $this->file_config->all['path'], 
                true
            );
        }
        else {
            $this->file_config->set('all.path.apptree', $apptree->tree);
        }

    //      echo '<pre>';
    // print_r($this->file_config);
    // echo '</pre>';
        

        $this->validate($this->file_config);

        // merge array section 'all' with current environment section if exists
        $this->file_config->mergeRecursiveDistinct($this->file_config->all, $this->file_config->$env);

 
        //set some php ini settings
        if(isset($this->file_config->php)) {
            $this->_processPHPconfig($this->file_config->php);
        }

        // final environement settings
        $this->env_config = $this->file_config->toArray();
    }


    /**
     * Validation application config
     * 
     * @param  object $conf
     */
    private function validate($conf)
    {
        // default env aka all
        if(!$this->file_config->have('all')) {
            throw new Exception(
                'ERR_CUSTOM', 
                'Your application doesn\'t have default "all" configuration'
            );
        }

        // current env
        if(!$this->file_config->have($this->app_config->env)) {
            throw new Exception(
                'ERR_CUSTOM', 
                'Your application doesn\'t have "'.$this->app_config->env.'" configuration'
            );
        }
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