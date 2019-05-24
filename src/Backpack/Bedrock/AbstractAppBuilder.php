<?php

declare(strict_types=1);

namespace Peak\Backpack\Bedrock;

use Peak\Blueprint\Backpack\AppBuilderBase;
use Peak\Blueprint\Bedrock\Kernel;
use Peak\Blueprint\Collection\Dictionary;
use Peak\Collection\DotNotationCollection;
use Psr\Container\ContainerInterface;
use Closure;
use Exception;

use function gettype;
use function is_array;

abstract class AbstractAppBuilder implements AppBuilderBase
{
    /**
     * @var string|null
     */
    protected $env;

    /**
     * @var ContainerInterface|null
     */
    protected $container;

    /**
     * @var Kernel|null
     */
    protected $kernel;

    /**
     * @var Dictionary|null
     */
    protected $props;

    /**
     * Application class
     * @var string|null
     */
    protected $appClass;

    /**
     * Kernel class
     * @var string|null
     */
    protected $kernelClass;

    /**
     * @var Closure|null
     */
    protected $afterBuild;

    /**
     * @param string $env
     * @return $this
     */
    public function setEnv(string $env)
    {
        $this->env = $env;
        return $this;
    }

    /**
     * @param ContainerInterface $container
     * @return $this
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
        return $this;
    }

    /**
     * @param $props
     * @return $this
     * @throws Exception
     */
    public function setProps($props)
    {
        if (!is_array($props) && !$props instanceof Dictionary) {
            throw new Exception('Props must be an array or an instance of Peak\Blueprint\Collection\Dictionary. '.gettype($props).' given ...');
        } elseif (is_array($props)) {
            $props = new DotNotationCollection($props);
        }

        $this->props = $props;
        return $this;
    }

    /**
     * @param Kernel $kernel
     * @return $this
     */
    public function setKernel(Kernel $kernel)
    {
        $this->kernel = $kernel;
        return $this;
    }

    /**
     * @param string $kernelClass
     * @return $this
     */
    public function setKernelClass(string $kernelClass)
    {
        $this->kernelClass = $kernelClass;
        return $this;
    }

    /**
     * @param string $appClass
     * @return $this
     */
    public function setAppClass(string $appClass)
    {
        $this->appClass = $appClass;
        return $this;
    }

    /**
     * @param Closure $fn
     * @return $this
     */
    public function executeAfterBuild(Closure $fn)
    {
        $this->afterBuild = $fn;
        return $this;
    }
}