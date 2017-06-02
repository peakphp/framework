<?php

namespace Peak\Bedrock\View;

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
    public function __construct(View $view, Cache $cache)
    {
        $this->view = $view;
        $this->cache = $cache;
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
    public function  __call($method, $args)
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
            include($this->cache()->getCacheFile());
            return;
        }

        //cache and output current view script
        ob_start();
        $this->output($data);
        //if(is_writable($cache_file)) { //fail if file cache doesn't already
        $content = ob_get_contents();
        //if($this->cache_strip) $content = preg_replace('!\s+!', ' ', $content);
        file_put_contents($this->cache()->getCacheFile(), $content);
        //}
        ob_get_flush();
    }

    /**
     * Access to cache object
     *
     * @return object Peak\Bedrock\View\Cache
     */
    public function cache()
    {
        return $this->cache;
    }
}
