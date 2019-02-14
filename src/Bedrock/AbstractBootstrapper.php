<?php

declare(strict_types=1);

namespace Peak\Bedrock;

use Peak\Bedrock\Bootstrap\BootableResolver;
use Peak\Blueprint\Bedrock\Application;
use Peak\Blueprint\Common\Bootable;
use Peak\Di\Container;

/**
 * Class AbstractBootstrapper
 * @package Peak\Bedrock\Application
 */
abstract class AbstractBootstrapper implements Bootable
{
    /**
     * Prefix of methods to call on boot
     * @var string
     */
    protected $bootMethodsPrefix = 'init';

    /**
     * @var Application
     */
    protected $application;

    /**
     * Bootable processes
     * @var array
     */
    protected $processes = [];

    /**
     * @var BootableResolver
     */
    protected $resolver;

    /**
     * AbstractBootstrapper constructor.
     *
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
        $this->resolver = new BootableResolver($application->getContainer());
    }

    /**
     * Boot
     *
     * @return bool|mixed
     * @throws \Exception
     */
    public function boot()
    {
        // call environment method if exists
        // e.g. envDev() envProd() envStating() envTesting()
        $envMethod = 'env'.ucfirst(strtolower($this->application->getKernel()->getEnv()));
        $this->call($envMethod);

        // run processes
        foreach ($this->processes as $process) {
            $processResolved = $this->resolver->resolve($process);
            $processResolved->boot();
        }

        // call method beginning by $bootMethodsPrefix
        $bootMethods = get_class_methods(get_class($this));
        if (!empty($bootMethods)) {
            $l = strlen($this->bootMethodsPrefix);
            foreach ($bootMethods as $bootMethod) {
                if (substr($bootMethod, 0, $l) === $this->bootMethodsPrefix) {
                    $this->call($bootMethod);
                }
            }
        }

        return true;
    }

    /**
     * @param string $method
     */
    private function call(string $method)
    {
        if (method_exists($this, $method)) {
            if ('Peak\Di\Container' === get_class($this->application->getContainer())) {
                $this->application->getContainer()->call([$this, $method]);
            } else {
                $this->$method();
            }
        }
    }
}
