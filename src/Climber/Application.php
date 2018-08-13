<?php

declare(strict_types=1);

namespace Peak\Climber;

use Peak\Bedrock\Application\Config;
use Peak\Di\Container;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Application as ConsoleApplication;

/**
 * Class Application
 * @package Peak\Climber
 */
class Application
{
    /**
     * Di Container
     * @var ContainerInterface
     */
    protected static $container;

    /**
     * Cli app config
     * @var Config
     */
    protected $config;

    /**
     * Symfony console application
     * @var ConsoleApplication
     */
    protected $console_app;

    /**
     * Access to current di container
     * @return ContainerInterface
     */
    public static function container()
    {
        return self::$container;
    }

    /**
     * Static version of config() use current Application instance in Registry
     *
     * @param  string $path
     * @param  mixed  $value
     * @return mixed
     */

    /**
     * @param null $path
     * @param null $value
     * @return mixed
     * @throws \Peak\Di\Exception\NotFoundException
     */
    public static function conf($path = null, $value = null)
    {
        $config = self::$container->get(Config::class);

        if (!isset($path)) {
            return $config;
        } elseif (!isset($value)) {
            return $config->get($path);
        }

        $config->set($path, $value);
        return $config;
    }

    /**
     * Cli constructor
     *
     * @param ContainerInterface|null $container
     */
    public function __construct(ContainerInterface $container = null, array $config = [])
    {
        if (!isset($container)) {
            $container = new Container();
        }

        // set di container
        self::$container = $container;

        // add app to the container
        self::container()->add($this);

        // set configuration
        $this->config = (new ConfigResolver($config))->getMountedConfig();
        self::container()->add($this->config);

        // load symfony console app
        $this->console_app = new ConsoleApplication($this->config->name, $this->config->version);
        self::container()->add($this->console_app);

        // load cli application bootstrapper
        $this->loadBootstrap();
    }

    /**
     * Call to unknown method to Symfony Console Application
     *
     * @param string $method
     * @param array  $args
     */
    public function __call($method, $args)
    {
        return call_user_func_array([$this->console_app, $method], $args);
    }

    /**
     * Load cli application bootstrapper if exists
     */
    public function loadBootstrap()
    {
        $cname = $this->config->get('ns').'\Bootstrap';
        if (class_exists($cname)) {
            $this->bootstrap = new $cname(self::container());
        }
    }
}
