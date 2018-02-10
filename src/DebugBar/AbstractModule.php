<?php

namespace Peak\DebugBar;

use Peak\Common\Collection;
use Peak\Common\Interfaces\Initializable;
use Peak\Common\Interfaces\Renderable;

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
     */
    public function renderLogo()
    {
        $logo_file = $this->getModulePath().'/logo.svg';
        if (file_exists($logo_file)) {
            return file_get_contents($logo_file);
        }
    }

    /**
     * Render the block
     *
     * @return string
     */
    final public function render()
    {
        $view_file = $this->getModulePath().'/view.php';

        if (!file_exists($view_file)) {
            return '';
        }

        return (new View(
            $view_file,
            $this->data->toArray())
        )->render();
    }
}