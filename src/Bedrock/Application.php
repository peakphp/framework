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
     * Framework version
     */
    const VERSION = '2.1.0';

    /**
     * Container instance
     * @var ContainerInterface
     */
    protected static $container;

    /**
     * Get application container
     *
     * @return Peak\Di\ContainerInterface
     */
    public static function container()
    {
        self::containerCheck();
        return self::$container;
    }

    /**
     * Get application kernel
     *
     * @return \Peak\Bedrock\Application\Kernel
     */
    public static function kernel()
    {
        return self::get('Peak\Bedrock\Application\Kernel');
    }

    /**
     * Set the container
     *
     * @param ContainerInterface $container
     */
    public static function setContainer(ContainerInterface $container)
    {
        self::$container = $container;
    }

    /**
     * Get container / instance in the container
     *
     * @param  string $instance
     * @return mixed
     */
    public static function get($instance)
    {
        self::containerCheck($instance);
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
        self::containerCheck();
        return self::$container->instantiate($class, $args, $explict);
    }

    /**
     * Check application container
     *
     * @param  string|null $instance
     */
    protected static function containerCheck($instance = null)
    {
        if (!(self::$container instanceof ContainerInterface)) {
            throw new \Exception(__CLASS__.' as no container');
        }
        if (isset($instance)) {
            if (!self::$container->hasInstance($instance)) {
                throw new \Exception(__CLASS__.' container does not have '.$instance);
            }
        }

        return $instance;
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
        $config = self::get(self::containerCheck('Peak\Bedrock\Application\Config'));

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
        self::setContainer($container);
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
        
        // instantiate and store app kernel
        $container->instantiateAndStore('\Peak\Bedrock\Application\Kernel'); 
    }

    /**
     * Run
     *
     * @see Peak\Bedrock\Application\Kernel
     */
    public function run($request = null)
    {
        return self::kernel()->run($request);
    }

    /**
     * Render
     *
     * @see Peak\Bedrock\Application\Kernel
     */
    public function render()
    {
        return self::kernel()->render();
    }
}