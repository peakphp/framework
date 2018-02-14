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
     * View data
     * @var Collection
     */
    protected $data;

    /**
     * @var SessionStorage
     */
    private $storage;

    /**
     * @var array
     */
    protected $default_storage_data = [];

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
     * Use View/assets/default-logo.svg if true and no module logo.svg file found
     * @var bool
     */
    protected $use_default_logo = false;

    /**
     * DebugBarBlock constructor.
     */
    public function __construct(SessionStorage $storage)
    {
        $this->module_path = $this->getModulePath();
        $this->data = new Collection();
        $this->storage = $storage;
        $this->initializeDefaultDataStorage();
        $this->initialize();
    }

    /**
     * Render the tab title
     * @return mixed
     */
    abstract public function renderTitle();

    /**
     * Initiate module data storage
     */
    protected function initializeDefaultDataStorage()
    {
        if (!isset($this->storage[$this->getName()]) && !empty($this->default_storage_data)) {
            $this->saveToStorage($this->default_storage_data);
        }
    }

    /**
     * Get module storage
     *
     * @return mixed
     */
    protected function getStorage()
    {
        return $this->storage[$this->getName()];
    }

    /**
     * Save data to module storage
     *
     * @param $data
     */
    protected function saveToStorage($data)
    {
        $this->storage->mergeWith([
            $this->getName() => $data
        ])->save();
    }

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
        } elseif ($this->use_default_logo) {
            return file_get_contents(__DIR__.'/View/assets/default-logo.svg');
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