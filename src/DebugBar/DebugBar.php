<?php

namespace Peak\DebugBar;

use Peak\Collection\Collection;
use Peak\Blueprint\Common\Renderable;
use Peak\DebugBar\Exception\InvalidModuleException;
use Peak\DebugBar\Exception\ModuleNotFoundException;
use Peak\DebugBar\View\Layout;
use Psr\Container\ContainerInterface;

/**
 * Class DebugBar
 * @package Peak\DebugBar
 */
class DebugBar implements Renderable
{
    /**
     * Default Modules List
     * @var array
     */
    protected $defaultModules = [
        \Peak\DebugBar\Modules\Peak\Peak::class,
        \Peak\DebugBar\Modules\PhpVersion\PhpVersion::class,
        \Peak\DebugBar\Modules\ExecutionTime\ExecutionTime::class,
        \Peak\DebugBar\Modules\Memory\Memory::class,
        \Peak\DebugBar\Modules\Message\Message::class,
        \Peak\DebugBar\Modules\Session\Session::class,
        \Peak\DebugBar\Modules\Inputs\Inputs::class,
        \Peak\DebugBar\Modules\Headers\Headers::class,
        \Peak\DebugBar\Modules\UserConstants\UserConstants::class,
    ];

    /**
     * Modules object instances
     * @var array
     */
    protected $modulesInstances = [];

    /**
     * @var ModuleResolver
     */
    protected $moduleResolver;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * DebugBar constructor
     */
    public function __construct(ContainerInterface $container = null)
    {
        $this->container = $container;
        $this->moduleResolver = new ModuleResolver($container);
    }

    /**
     * Add module
     *
     * @param mixed $module
     * @return $this
     */
    public function addModule($module)
    {
        $instance = $this->moduleResolver->resolve($module);
        $this->modulesInstances[get_class($instance)] = $instance;
        return $this;
    }

    /**
     * Add modules
     *
     * @param array $modules
     * @return $this
     */
    public function addModules(array $modules)
    {
        foreach ($modules as $module) {
            $this->addModule($module);
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function addDefaultModules()
    {
        $this->setModules($this->defaultModules);
        return $this;
    }

    /**
     * Overwrite modules
     *
     * @param array $modules
     * @return $this
     */
    public function setModules(array $modules)
    {
        $this->modulesInstances = [];
        foreach ($modules as $module) {
            $this->addModule($module);
        }
        return $this;
    }

    /**
     * Get module
     * @param string $module
     * @return mixed
     * @throws InvalidModuleException
     * @throws ModuleNotFoundException
     * @throws \ReflectionException
     */
    public function getModule(string $module)
    {
        return $this->getModuleInstance($module);
    }

    /**
     * Render
     */
    public function render()
    {
        $content = '';
        $tabs = [];

        foreach ($this->modulesInstances as $module) {
            $module->preRender();

            if ($module->isRenderDisabled()) {
                continue;
            }

            $tab = $module->renderTitle();
            $logo = $module->renderLogo();
            if (!empty($tab) && !empty($logo)) {
                $tab = $logo.' '.$tab;
            }
            $tabs[$module->getName()] = $tab;
            $content .= $this->renderModule($module);
        }

        $layout_content = new Collection([
            'tabs' => $tabs
        ]);

        return (new Layout(
            __DIR__.'/View/scripts/bar.layout.php',
                $layout_content,
                $content
            ))->render();
    }

    /**
     * Get module instance
     *
     * @param string $module
     * @return mixed
     * @throws InvalidModuleException
     * @throws ModuleNotFoundException
     * @throws \ReflectionException
     */
    protected function getModuleInstance(string $module)
    {
        if (!array_key_exists($module, $this->modulesInstances)) {
            $module = $this->moduleResolver->resolve($module);
            $this->modulesInstances[get_class($module)] = $module;
        }

        return $this->modulesInstances[$module];
    }

    /**
     * Render a module an throw also proper warning in cass module doesn't implement Renderable
     *
     * @param Renderable $module
     * @return mixed
     */
    protected function renderModule(Renderable $module)
    {
        return $module->render();
    }
}
