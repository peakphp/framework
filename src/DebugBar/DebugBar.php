<?php

namespace Peak\DebugBar;

use Peak\Common\Interfaces\Renderable;
use Peak\Common\Session;


/**
 * Debug bar
 */
class DebugBar implements Renderable
{
    /**
     * Default Modules List
     * @var array
     */
    protected $modules = [
        \Peak\DebugBar\Modules\ExecutionTime\ExecutionTime::class,
        \Peak\DebugBar\Modules\Memory\Memory::class,
        \Peak\DebugBar\Modules\Message\Message::class,
        \Peak\DebugBar\Modules\Files\Files::class,
        \Peak\DebugBar\Modules\Session\Session::class,
        \Peak\DebugBar\Modules\Inputs\Inputs::class,
    ];

    /**
     * Modules object instances
     * @var array
     */
    protected $modules_instances = [];

    /**
     * DebugBar constructor
     */
    public function __construct(array $modules = [])
    {
        if (!empty($modules)) {
            $this->setModules($modules);
        }
        $this->initializeSession();
    }

    /**
     * Initialize stuff if session started
     */
    protected function initializeSession()
    {
//        if (Session::isStarted()) {
//            if (!isset($_SESSION['pkdebugbar'])) {
//                $_SESSION['pkdebugbar'] = serialize([
//                    'chronometers' => []
//                ]);
//            }
//        }
    }

    /**
     * Add module
     *
     * @param string $module
     * @return $this
     */
    public function addModule($module)
    {
        $this->modules[] = $module;
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
        $this->modules = $modules;
        return $this;
    }

    /**
     * Render debug bar modules
     *
     * @return string
     */
    public function render()
    {
        $content = '';
        $tabs = [];
        foreach ($this->modules as $module) {
            $module_obj = new $module();
            if ($module_obj->isRenderDisabled()) {
                continue;
            }

            $tab = $module_obj->renderTitle();
            $logo = $module_obj->renderLogo();
            if (!empty($tab) && !empty($logo)) {
                $tab = $logo.' '.$tab;
            }
            $tabs[$module_obj->getName()] = $tab;

            $content .= $this->renderModule($module_obj);
        }

        return (new View(__DIR__.'/View/layout.php', $content))
            ->renderLayout($content, $tabs);
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
