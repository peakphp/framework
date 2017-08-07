<?php

namespace Peak\Bedrock\View;

use Peak\Bedrock\Application;
use Peak\Bedrock\View;

/**
 * View Cache
 *
 * This object manage view cache and it is encapsulated inside Peak\Bedrock\View\Render
 */
class Cache
{
    protected $use_cache = false;     //use scripts view cache, false by default
    protected $cache_expire;          //script cache expiration time
    protected $cache_id;              //current script view md5 key. generate by preOutput()
    protected $cache_strip = false;   //will strip all repeating space characters

    /**
     * Path where cache are stored
     * @var string
     */
    protected $path;

    /**
     * View instance
     * @var View
     */
    protected $view;

    /**
     * Set cache folder
     */
    public function __construct(View $view, $path)
    {
        $this->view = $view;
        $this->setPath($path);
    }

    /**
     * Set cache path
     * @param string $path
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;
        $this->createCachePath();
        return $this;
    }
    /**
     * Enable output caching.
     * Avoid using in controllers actions that depends on $_GET, $_POST or any dynamic value for setting the view
     *
     * @param integer $time set cache ttl(in seconds) i.e; cache will be regenerated after each $time;
     */
    public function enable($time)
    {
        if (is_integer($time)) {
            $this->use_cache = true;
            $this->cache_expire = $time;
        }
    }

    /**
     * Deactivate output cache
     */
    public function disable()
    {
        $this->use_cache = false;
    }
    
    /**
     * Check if cache is enabled
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->use_cache;
    }

    /**
     * Check if current view script file is cached/expired
     * Note: if $this->cache_id is not set, this will generate a new id from $id params if set or
     * from the current controller file - path
     * Notes: Custom $id can lead to problems if used with controller redirection * need to be fix
     *
     * @return bool
     */
    public function isValid($id = null)
    {
        if ($this->use_cache === false) {
            return false;
        }

        //when checking isValid without a custom id in controller action we generated a new id based
        //on controller name and action name. If id is null but, cache id is already generated, we use it.
        if (is_null($id)) {
            if (is_null($this->cache_id)) {
                //bug
                $kernel = Application::kernel();
                $this->generateId(
                    shortClassName($kernel->front->controller),
                    $kernel->front->controller->action
                );
            }
        } else {
            $this->generateId(null, $id);
        }

        $filepath = $this->getFile();

        if (file_exists($filepath)) {
            $file_date = filemtime($filepath);
            $now = time();
            $delay = $now - $file_date;
            return ($delay >= $this->cache_expire) ? false : true;
        }
        return false;
    }

    /**
     * Generate md5 cache id from script view filename and path by default
     * Set a $path and $file to generate a new custom id.
     *
     * @param string $path
     * @param string $file
     */
    public function generateId($path = null, $file = null, $return = false)
    {
        //use current $this->_script_file and _script_path if no path/file specified
        if (!isset($path) && !isset($file)) {
            $path = $this->view->engine()->scripts_path;
            $file = $this->view->engine()->scripts_file;
        }

        $key = $path.$file;

        $cache_id = hash('md5', $key);

        if ($return) {
            return $cache_id;
        }

        $this->cache_id = $cache_id;
    }

    /**
     * Get current cached view script complete filepath
     *
     * @return string
     */
    public function getFile()
    {
        return $this->path.'/'.$this->cache_id.'.php';
    }

    /**
     * Enable/disable cache compression
     *
     * @param bool $status
     */
    public function enableStrip($status)
    {
        if (is_bool($status)) {
            $this->cache_strip = $status;
        }
    }

    /**
     * Allow caching block inside views
     * Check if a custom cache block is expired
     *
     * @param  string $id
     * @param  integer $expiration
     * @return bool
     */
    public function isValidBlock($id, $expiration)
    {
        $this->enable($expiration);

        if ($this->isValid($id)) {
            return true;
        }

        return false;
    }

    /**
     * Start a block cache
     */
    public function blockStart()
    {
        ob_start();
    }

    /**
     * Close buffer of a cache block previously started by isCachedBlock()
     */
    public function blockEnd()
    {
        file_put_contents($this->getFile(), preg_replace('!\s+!', ' ', ob_get_contents()));
        ob_get_flush();
    }

    /**
     * Get cache content block
     */
    public function getContent()
    {
        include $this->getFile();
    }

    /**
     * Delete current cache file or custom cache file id
     *
     * @param string $id
     */
    public function delete($id = null)
    {
        if (isset($id)) {
            $this->generateId('', $id);
        }

        $file = $this->getFile();
        if (file_exists($file)) {
            unlink($file);
        }
    }

    /**
     * Attempt to create the cache path
     * @throws \Exception
     */
    protected function createCachePath()
    {
        if (!file_exists($this->path)) {
            if(!@mkdir($this->path, 755, true)) {
                throw new \Exception('Cannot create cache folder at '.$this->path);
            }
        }
    }
}
