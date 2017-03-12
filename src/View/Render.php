<?php

namespace Peak\View;

use Peak\Bedrock\Application\Container;
use Peak\View;
use Peak\View\Cache;

/**
 * Peak_View_Render Engine base
 */
abstract class Render
{

    public $scripts_file;          //controller action script view path used 
    public $scripts_path;          //controller action script view file name used

    protected $cache;              //view cache object

    protected $view;

    //force child to implement those functions
    abstract public function render($file, $path = null);
    abstract protected function output($data);

    public function __construct(View $view)
    {
        $this->view = $view;
    }

    /**
     * Same as render() but handle an array of file instead
     *
     * @param  array  $files
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
     * Point to Peak_View __get method
     *
     * @param  string $name represent view var name
     * @return misc
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
     * Silent call to unknow method or
     * Throw trigger error when DEV_MODE is activated 
     *
     * @param string $method
     * @param array  $args
     */
    public function  __call($method, $args)
    {
        $view =& $this->view;
        return call_user_func_array(array($view, $method), $args);
    }

    /**
     * Call child output method and cache it if cache activated;
     * Can be overloaded by engines to customize how the cache data
     *
     * @param misc $data
     */
    protected function preOutput($data)
    {
        if (!$this->cache()->isEnabled()) {
            $this->output($data);
            return;
        }

        //use cache instead outputing and evaluating view script
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
     * @return object Peak\View\Cache
     */
    public function cache()
    {
        if (!is_object($this->cache)) {
            $this->cache = Container::instantiate('Peak\View\Cache');
        }
        return $this->cache;
    }
}
