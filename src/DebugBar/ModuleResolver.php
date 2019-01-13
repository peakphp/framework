<?php

declare(strict_types=1);

namespace Peak\DebugBar;

use Peak\Blueprint\Common\ResourceResolver;
use Peak\DebugBar\Exception\InvalidModuleException;
use Peak\DebugBar\Exception\ModuleNotFoundException;
use Peak\Di\Container;
use Psr\Container\ContainerInterface;

/**
 * Class ModuleResolver
 * @package Peak\DebugBar
 */
class ModuleResolver implements ResourceResolver
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Resolver constructor.
     *
     * @param null|ContainerInterface $container
     */
    public function __construct(?ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param mixed $module
     * @return mixed|object
     * @throws InvalidModuleException
     * @throws ModuleNotFoundException
     * @throws \Peak\Di\Exception\NoClassDefinitionException
     * @throws \ReflectionException
     */
    public function resolve($module)
    {
        if ($module instanceof AbstractModule)  {
            return $module;
        }

        if (is_string($module)) {
            return $this->resolveString($module);
        }

        throw new InvalidModuleException($module);
    }

    /**
     * @param string $module
     * @return mixed|object
     * @throws ModuleNotFoundException
     * @throws \Peak\Di\Exception\NoClassDefinitionException
     * @throws \ReflectionException
     */
    protected function resolveString(string $module)
    {
        if (!class_exists($module)) {
            throw new ModuleNotFoundException($module);
        }

        // resolve using a container
        if (null !== $this->container) {
            if ($this->container->has($module)) { // psr-11
                return $this->container->get($module);
            } elseif ($this->container instanceof Container) {
                return $this->container->create($module, [], [AbstractStorage::class => new SessionStorage()]);
            }
        }

        // manual instantiation
        return new $module();
    }
}
