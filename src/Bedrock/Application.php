<?php

declare(strict_types=1);

namespace Peak\Bedrock;

use Peak\Bedrock\Application\Config;
use Peak\Bedrock\Application\ConfigResolver;
use Peak\Bedrock\Application\Exceptions\InstanceNotFoundException;
use Peak\Bedrock\Application\Exceptions\MissingContainerException;
use Peak\Bedrock\Application\Kernel;
use Peak\Bedrock\Application\Routing;
use Psr\Container\ContainerInterface;

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
     * @throws InstanceNotFoundException
     * @throws MissingContainerException
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
     * @param $instance
     * @return mixed
     * @throws InstanceNotFoundException
     * @throws MissingContainerException
     */
    public static function get($instance)
    {
        self::containerCheck($instance);
        return self::$container->get($instance);
    }

    /**
     * Instantiate a class
     *
     * @param $class
     * @param array $args
     * @param array $explicit
     * @return mixed
     * @throws InstanceNotFoundException
     * @throws MissingContainerException
     */
    public static function create($class, $args = [], $explicit = [])
    {
        self::containerCheck();
        return self::$container->create($class, $args, $explicit);
    }

    /**
     * Check application container
     *
     * @param null $instance
     * @return null
     * @throws InstanceNotFoundException
     * @throws MissingContainerException
     */
    protected static function containerCheck($instance = null)
    {
        if (!(self::$container instanceof ContainerInterface)) {
            throw new MissingContainerException();
        }
        if (isset($instance)) {
            if (!self::$container->has($instance) && !self::$container->hasAlias($instance)) {
                throw new InstanceNotFoundException($instance);
            }
        }

        return $instance;
    }

    /**
     * Static version of config() use current Application instance in Registry
     *
     * @param null $path
     * @param null $value
     * @return mixed
     * @throws InstanceNotFoundException
     * @throws MissingContainerException
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
     * @param ContainerInterface $container
     * @param array $config
     * @throws Application\Exceptions\MissingConfigException
     * @throws Application\Exceptions\PathNotFoundException
     * @throws \Peak\Config\Exception\UnknownResourceException
     */
    protected function build(ContainerInterface $container, array $config)
    {
        $config_resolver = new ConfigResolver($config);

        // store Peak\Bedrock\Application\Config
        $container->add($config_resolver->getMountedConfig(), 'AppConfig');

        // store Peak\Bedrock\Application\Routing
        $container->add(new Routing, 'AppRouting');

        // store Peak\Bedrock\View
        $container->add(new View, 'AppView');
        
        // instantiate and store app kernel
        $container->createAndStore(Kernel::class);
        $container->addAlias('AppKernel', Kernel::class);
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
