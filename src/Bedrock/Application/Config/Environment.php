<?php

namespace Peak\Bedrock\Application\Config;

use \Exception;
use Peak\Common\Collection;
use Peak\Bedrock\Application\Config\AppTree;

class Environment
{
    /**
     * App file config
     * @var Peak\Common\Collection
     */
    protected $file_config;

    /**
     * App default config
     * @var Peak\Common\Collection
     */
    protected $app_config;

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
        $this->processEnv();
    }

    /**
     * Get the final app config
     *
     * @return array
     */
    public function getEnvConfig()
    {
        return $this->file_config->toArray();
    }

    /**
     * Process app file config with current environment
     */
    private function processEnv()
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
        } else {
            $this->file_config->set('all.path.apptree', $apptree->tree);
        }

        $this->validate();

        // merge array section 'all' with current environment section if exists
        $this->file_config->mergeRecursiveDistinct($this->file_config->all, $this->file_config->$env);
    }

    /**
     * Validation application config
     */
    private function validate()
    {
        // default env aka all
        if (!$this->file_config->have('all')) {
            throw new Exception('Your application doesn\'t have default "all" configuration');
        }

        // current env
        if (!$this->file_config->have($this->app_config->env)) {
            throw new Exception('Your application doesn\'t have "'.$this->app_config->env.'" configuration');
        }
    }
}
