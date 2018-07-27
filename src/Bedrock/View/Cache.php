<?php

declare(strict_types=1);

namespace Peak\Bedrock\View;

use Peak\Bedrock\Application;
use Peak\Bedrock\View;
use Peak\Common\TimeExpression;
use \Exception;

/**
 * Class Cache
 * @package Peak\Bedrock\View
 */
class Cache
{
    /**
     * Activate/Deactivate cache
     * @var bool
     */
    protected $use_cache = false;

    /**
     * Cache expiration time in sec
     * @var integer
     */
    protected $cache_expire;

    /**
     * Cache unique id
     * @var string
     */
    protected $cache_id;

    /**
     * Strip all repeating spaces characters before saving cache content
     * @var bool
     */
    protected $trim_spaces = false;

    /**
     * Compress cache file using bzip2
     * @var bool
     */
    protected $compress = false;

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
     * @param mixed $time set cache ttl(in seconds) i.e; cache will be regenerated after each $time;
     * @return $this
     */
    public function enable($time)
    {
        $time = (new TimeExpression($time))->toSeconds();
        if (is_integer($time)) {
            $this->use_cache = true;
            $this->cache_expire = $time;
        }
        return $this;
    }

    /**
     * Deactivate output cache
     *
     * @return $this
     */
    public function disable()
    {
        $this->use_cache = false;
        return $this;
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
     * Notes:
     *  - If $this->cache_id is not set, this will generate a new id from $id params if set or
     *    from the current controller file path
     *  - Custom $id can lead to collision if used with controller redirection
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
     * @return $this
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
        return $this;
    }

    /**
     * Get current cached view script complete file path
     *
     * @return string
     */
    public function getFile()
    {
        return $this->path.'/'.$this->cache_id.'.php';
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
     * Indicate if multiple spaces must be stripped from cache file
     *
     * @param bool $status
     */
    public function trimSpaces($status)
    {
        $this->trim_spaces = $status;
        return $this;
    }

    /**
     * Indicate if cache file is using compression
     *
     * @param $status
     * @return $this
     */
    public function compress($status)
    {
        $this->compress = $status;
        return $this;
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
        $content = ob_get_flush();
        $this->saveContent($content);
    }

    /**
     * Get cache content block
     */
    public function getContent()
    {
        $content = file_get_contents($this->getFile());
        if ($this->compress) {
            $content = bzdecompress($content);
        }
        return $content;
    }

    /**
     * Save cache content to his respective file
     *
     * @param string $content
     */
    public function saveContent($content)
    {
        if ($this->trim_spaces) {
            $content = preg_replace('!\s+!', ' ', $content);
        }
        if ($this->compress === true) {
            $content = bzcompress($content);
        }
        file_put_contents($this->getFile(), $content);
    }

    /**
     * Delete current cache file or custom cache file id
     *
     * @param string $id
     * @return $this
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
        return $this;
    }

    /**
     * Attempt to create the cache path
     * @throws \Exception
     */
    protected function createCachePath()
    {
        if (!file_exists($this->path)) {
            if (!@mkdir($this->path, 755, true)) {
                throw new Exception('Cannot create cache folder at '.$this->path);
            }
        }
    }
}
