<?php

namespace Peak\Bedrock;

use Peak\Bedrock\Application\Config;
use Peak\Bedrock\Application\ConfigResolver;
use Peak\Bedrock\Application\Kernel;
use Peak\Bedrock\Application\Routing;
use Psr\Container\ContainerInterface;
use \Exception;

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
     * Get application container
     *
     * @return ContainerInterface
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
        return self::get(Kernel::class);
    }

    /**
     * Set the container
     *
     * @param  ContainerInterface $container
     * @return ContainerInterface
     */
    public static function setContainer(ContainerInterface $container)
    {
        self::$container = $container;
        return $container;
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
        return self::$container->get($instance);
    }

    /**
     * Instantiate a class
     *
     * @see \Peak\Di\Container for details
     * @return mixed
     */
    public static function create($class, $args = [], $explicit = [])
    {
        self::containerCheck();
        return self::$container->create($class, $args, $explicit);
    }

    /**
     * Check application container
     *
     * @param  string|null $instance
     */
    protected static function containerCheck($instance = null)
    {
        if (!(self::$container instanceof ContainerInterface)) {
            throw new Exception(__CLASS__.' has no container');
        }
        if (isset($instance)) {
            if (!self::$container->has($instance) && !self::$container->hasAlias($instance)) {
                throw new Exception(__CLASS__.' container does not have '.$instance);
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
        $config = self::get(self::containerCheck(Config::class));

        if (!isset($path)) {
            return $config;
        } elseif (!isset($value)) {
            return $config->get($path);
        }

        $config->set($path, $value);
        return $config;
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
        $this->build($container, $config);
    }

    /**
     * Build application base
     *
     * @param  array $config
     * @return Application
     */
    protected function build(ContainerInterface $container, array $config)
    {
        $config_resolver = new ConfigResolver($config);

        // store Peak\Bedrock\Application\Config
        $container->add($config_resolver->getMountedConfig());

        // store Peak\Bedrock\Application\Routing
        $container->add(new Routing);

        // store Peak\Bedrock\View
        $container->add(new View);
        
        // instantiate and store app kernel
        $container->createAndStore(Kernel::class);
    }

    /**
     * Run
     *
     * @see Peak\Bedrock\Application\Kernel
     */
    public function run($request = null)
    {
        return $this->kernel()->run($request);
    }

    /**
     * Render
     *
     * @see Peak\Bedrock\Application\Kernel
     */
    public function render()
    {
        $this->kernel()->render();
    }
}
