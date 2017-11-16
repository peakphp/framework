<?php

namespace Peak\Bedrock\View;

use Peak\Bedrock\Application;
use Peak\Bedrock\View;
use Peak\Bedrock\View\Cache;

/**
 * Render Engine base
 */
abstract class Render
{
    /**
     * Controller action script view path used
     * @var string
     */
    public $scripts_file;

    /**
     * Controller action script view file name used
     * @var string
     */
    public $scripts_path;

    /**
     * Cache object
     * @var \Peak\Bedrock\View\Cache
     */
    protected $cache;

    /**
     * View object
     * @var \Peak\Bedrock\View
     */
    protected $view;

    //force child to implement those functions
    abstract public function render($file, $path = null);
    abstract protected function output($data);

    /**
     * Constructor
     *
     * @param View  $view
     * @param Cache $cache
     */
    public function __construct(View $view)
    {
        $this->view = $view;
    }

    /**
     * Same as render() but handle an array of file instead
     *
     * @param array $files
     */
    public function renderArray(array $files)
    {
        if (!empty($files)) {
            foreach ($files as $k => $v) {
                if (!is_numeric($k)) {
                    $this->render($k, $v); // file is path
                } else {
                    $this->render($v);
                }
            }
        }
    }

    /**
     * Render a block
     *
     * @param $block
     * @param array $block_data
     */
    public function renderBlock($block, array $block_data = [])
    {
        (new Block($this->view, $block, $block_data))->render();
    }

    /**
     * Point to View __get method
     *
     * @param  string $name represent view var name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->view->$name;
    }

    /**
     * Point to View __isset method
     *
     * @param  string $name represent view var name
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->view->$name);
    }

    /**
     * Silent call to unknown method
     *
     * @param string $method
     * @param array  $args
     */
    public function __call($method, $args)
    {
        $view =& $this->view;
        return call_user_func_array([$view, $method], $args);
    }

    /**
     * Call child output method and cache it if cache activated
     * Can be overloaded by engines to customize how the cache data
     *
     * @param mixed $data
     */
    protected function preOutput($data)
    {
        if (!$this->cache()->isEnabled()) {
            $this->output($data);
            return;
        }

        //use cache instead outputting and evaluating view script
        if ($this->cache()->isValid()) {
            echo $this->cache->getContent();
            return;
        }

        //cache and output current view script
        ob_start();
        $this->output($data);
        $content = ob_get_contents();
        $this->cache->saveContent($content);
        ob_get_flush();
    }

    /**
     * Access to cache object
     *
     * @return object Peak\Bedrock\View\Cache
     */
    public function cache()
    {
        if (!$this->cache instanceof Cache) {
            $this->cache = Application::create(Cache::class, [
                Application::conf('path.app').'/../cache/views'
            ]);
        }
        return $this->cache;
    }
}
