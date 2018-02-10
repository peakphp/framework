<?php

namespace Peak\DebugBar;

use Peak\Common\Collection;
use Peak\Common\Interfaces\Initializable;
use Peak\Common\Interfaces\Renderable;
use Peak\DebugBar\View\Layout;
use Peak\DebugBar\View\View;
use Peak\DebugBar\View\ViewNotFoundException;

abstract class AbstractModule implements Renderable, Initializable
{
    /**
     * Block data
     * @var Collection
     */
    protected $data;

    /**
     * Disable render flag
     * @var bool
     */
    protected $disable_render = false;

    /**
     * Module absolute path
     * @var string
     */
    private $module_path;

    /**
     * DebugBarBlock constructor.
     */
    public function __construct()
    {
        $this->module_path = $this->getModulePath();
        $this->data = new Collection();
        $this->initialize();
    }

    abstract public function renderTitle();

    /**
     * Get module absolute path
     *
     * @return string
     */
    protected function getModulePath()
    {
        if (empty($this->module_path)) {
            $this->module_path = dirname(getClassFilePath($this));
        }
        return $this->module_path;
    }

    /**
     * Get module view file path
     *
     * @return string
     */
    protected function getModuleView()
    {
        return $this->getModulePath().'/view.php';
    }

    /**
     * Get module layout file path
     *
     * @return string
     */
    protected function getModuleLayout()
    {
        return __DIR__.'/View/scripts/window.layout.php';
    }

    /**
     * Disable render flag
     *
     * @param bool $bool
     * @return $this
     */
    protected function disableRender($bool = true)
    {
        $this->disable_render = $bool;
        return $this;
    }

    /**
     * Check if render is disabled
     *
     * @return bool
     */
    public function isRenderDisabled()
    {
        return $this->disable_render;
    }

    /**
     * Get module name
     *
     * @return string
     */
    public function getName()
    {
        return strtolower(shortClassName($this));
    }

    /**
     * Render SVG logo if file exists
     *
     * @return bool|string
     */
    public function renderLogo()
    {
        $logo_file = $this->getModulePath().'/logo.svg';
        if (file_exists($logo_file)) {
            return file_get_contents($logo_file);
        }
        return '';
    }

    /**
     * Render the module with DebugBar window layout
     *
     * @return string
     * @throws ViewNotFoundException
     */
    public function render()
    {
        $file = $this->getModuleView();
        $layout = $this->getModuleLayout();

        if (!file_exists($file)) {
            return '';
        }

        $module_content = (new View($file, $this->data))->render();

        $data = new Collection([
            'window' => $this->getName()
        ]);

        return (new Layout($layout, $data, $module_content))->render();

    }
}