<?php

namespace Peak\DebugBar;

use Peak\Collection\Collection;
use Peak\Blueprint\Common\Initializable;
use Peak\Blueprint\Common\Renderable;
use Peak\DebugBar\View\Layout;
use Peak\DebugBar\View\View;
use Peak\DebugBar\View\ViewNotFoundException;

/**
 * Class AbstractModule
 * @package Peak\DebugBar
 */
abstract class AbstractModule implements Renderable, Initializable
{
    /**
     * View data
     * @var Collection
     */
    protected $data;

    /**
     * Disable render flag
     * @var bool
     */
    protected $disableRender = false;

    /**
     * Module absolute path
     * @var string
     */
    protected $modulePath;

    /**
     * Use View/assets/default-logo.svg if true and no module logo.svg file found
     * @var bool
     */
    protected $useDefaultLogo = false;

    /**
     * DebugBarBlock constructor.
     */
    public function __construct()
    {
        $this->modulePath = $this->getModulePath();
        $this->data = new Collection();
        $this->initialize();
    }

    /**
     * Render the tab title
     * @return mixed
     */
    abstract public function renderTitle();

    /**
     * Get module absolute path
     * @return mixed|string
     * @throws \ReflectionException
     */
    protected function getModulePath()
    {
        if (empty($this->modulePath)) {
            $this->modulePath = dirname(getClassFilePath($this));
        }
        return $this->modulePath;
    }

    /**
     * @return string
     * @throws \ReflectionException
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
        $this->disableRender = $bool;
        return $this;
    }

    /**
     * Check if render is disabled
     *
     * @return bool
     */
    public function isRenderDisabled()
    {
        return $this->disableRender;
    }

    /**
     * Get module name
     *
     * @return string
     * @throws \ReflectionException
     */
    public function getName()
    {
        return strtolower((new \ReflectionClass($this))->getShortName());
    }

    /**
     * Render SVG logo if file exists
     * @return false|string
     * @throws \ReflectionException
     */
    public function renderLogo()
    {
        $logo_file = $this->getModulePath().'/logo.svg';
        if (file_exists($logo_file)) {
            return file_get_contents($logo_file);
        } elseif ($this->useDefaultLogo) {
            return file_get_contents(__DIR__.'/View/assets/default-logo.svg');
        }
        return '';
    }

    /**
     * Execute before renderTitle(), renderLogo() and render()
     */
    public function preRender()
    {
        // nothing to do by default
    }

    /**
     * Render the module with DebugBar window layout
     * @return string|null
     * @throws ViewNotFoundException
     * @throws \ReflectionException
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
