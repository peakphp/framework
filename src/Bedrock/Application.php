<?php

namespace Peak\Bedrock;

use Peak\Di\ContainerInterface;
use Peak\Bedrock\Application\ConfigResolver;
use Peak\Bedrock\Application\Kernel;
use Peak\Bedrock\Application\Routing;

/**
 * Application wrapper
 */
class Application
{
    /**
     * Container instance
     * @var ContainerInterface
     */
    protected static $container;

    /**
     * Set the container
     *
     * @param ContainerInterface $container
     */
    public static function set(ContainerInterface $container)
    {
        self::$container = $container;
    }

    /**
     * Get container / instance in the container
     *
     * @param  string|null $instance
     * @return mixed
     */
    public static function get($instance = null)
    {
        if (!isset($instance)) {
            return self::$container;
        }

        return self::$container->getInstance($instance);
    }

    /**
     * Instantiate a class
     *
     * @see \Peak\Di\Container for details
     * @return mixed
     */
    public static function instantiate($class, $args = [], $explict = [])
    {
        return self::$container->instantiate($class, $args, $explict);
    }

    /**
     * Static version of config() use current Application instance in Registry
     *
     * @param  string $path
     * @param  mixed  $value
     * @return $this
     */
    public static function conf($path = null, $value = null)
    {
        $config = self::get('Peak\Bedrock\Application\Config');

        if (!isset($path)) {
            return $config;
        } elseif (!isset($value)) {
            return $config->get($path);
        }

        $config->set($path, $value);
        return $this;
    }

    /**
     * Create application
     *
     * @param ContainerInterface $container
     * @param array              $config
     */
    public function __construct(ContainerInterface $container, array $config)
    {
        self::set($container);
        $this->create($container, $config);
    }

    /**
     * Create a instance of application
     *
     * @param  array $config
     * @return Application
     */
    protected function create(ContainerInterface $container, array $config)
    {
        $config_resolver = new ConfigResolver($config);

        // store Peak\Bedrock\Application\Config
        $container->addInstance($config_resolver->getMountedConfig());

        // store Peak\Bedrock\Application\Routing
        $container->addInstance(new \Peak\Bedrock\Application\Routing);

        // store Peak\View
        $container->addInstance(new \Peak\View);
        
        // instantiate  and store app kernel
        $container->addInstance(
            $container->instantiate('\Peak\Bedrock\Application\Kernel')
        );
    }

    /**
     * Run
     *
     * @see Peak\Bedrock\Application\Kernel
     */
    public function run($request = null)
    {
        return self::get('Peak\Bedrock\Application\Kernel')->run($request);
    }

    /**
     * Render
     *
     * @see Peak\Bedrock\Application\Kernel
     */
    public function render()
    {
        return self::get('Peak\Bedrock\Application\Kernel')->render();
    }
}