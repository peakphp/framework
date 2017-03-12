<?php

namespace Peak\Bedrock;

use Peak\Di\Container;
use Peak\Bedrock\Application\Container as AppContainer;
use Peak\Bedrock\Application\ConfigResolver;
use Peak\Bedrock\Application\Kernel;
use Peak\Bedrock\Application\Routing;

class Application
{
    /**
     * Create application
     *
     * @param Container $container
     * @param array     $config   
     */
    public function __construct(Container $container, array $config)
    {
        AppContainer::set($container);
        $this->create($container, $config);
    }

    /**
     * Create a instance of application
     *
     * @param  array $config
     * @return Application
     */
    protected function create(Container $container, array $config)
    {
        $config_resolver = new ConfigResolver($config);

        // store Peak\Bedrock\Application\Config
        $container->addInstance($config_resolver->getMountedConfig());

        // store Peak\Bedrock\Application\Routing
        $container->addInstance(new \Peak\Bedrock\Application\Routing);

        // store Peak\View
        $container->addInstance(new \Peak\View);
        
        // instanciate app kernel
        $container->addInstance(
            $container->instantiate('\Peak\Bedrock\Application\Kernel')
        );


        // $container->addInstance(
        //     $this
        // );

        //print_r($container);
    }


    /**
     * Static version of config() use current Application instance in Registry
     *
     * @return $this
     */
    public static function conf($path = null, $value = null)
    {
        $app_config = AppContainer::get('Peak\Bedrock\Application\Config');

        if (!isset($path)) {
            return $app_config;
        } elseif (!isset($value)) {
            return $app_config->get($path);
        }

        $app_config->set($path, $value);
        return $this;
    }

    public function run($request = null)
    {
        return AppContainer::get('Peak\Bedrock\Application\Kernel')->run($request);
    }

    public function render()
    {
        return AppContainer::get('Peak\Bedrock\Application\Kernel')->render();
    }

}