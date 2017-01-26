<?php
namespace Peak\View;

use Peak\Application;
use Peak\Registry;
use Peak\View\Cache;

/**
 * Peak_View_Render Engine base
 */
abstract class Render
{

    public $scripts_file;          //controller action script view path used 
    public $scripts_path;          //controller action script view file name used

    protected $_cache;             //view cache object

	//force child to implement those functions
    abstract public function render($file, $path = null);
	abstract protected function output($data);

    /**
     * Same as render() but handle an array of file instead
     * 
     * @param  array  $files
     */
    public function renderArray(array $files)
    {
        if(!empty($files)) {
            foreach($files as $k => $v) {
                if(!is_numeric($k)) $this->render($k, $v); // file is path
                else $this->render($v);
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
        return Registry::o()->view->$name;
    }

    /**
     * Point to View __isset method
     *
     * @param  string $name represent view var name
     * @return bool
     */
    public function __isset($name)
    {
        return isset(Registry::o()->view->$name);
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
    	$view =& Registry::o()->view;
    	return call_user_func_array(array($view, $method), $args);
    }

    /**
     * Return public root url of your application
     *
     * @param  string $path Add custom paths/files to the end
     * @return string
     */
    public function baseUrl($path = null, $return = false)
    {
    	$schema_name = (isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) === 'on')) ? 'https://': 'http://';
        
        if(defined('PUBLIC_URL')) $url = PUBLIC_URL.'/'.$path;
        elseif(isset($_SERVER['SERVER_NAME'])) {
            $url = $schema_name.$_SERVER['SERVER_NAME'].'/'.relativePath(Application::conf('path.public')).'/'.$path;
        }

        //remove double slash(//) inside url
        $url_part    = explode($schema_name, $url);
        $url         = $schema_name.str_replace(array('///','//'),'/',$url_part[1]);
        
        if(!$return) echo $url;  
        else return $url;
    }

    /**
     * Generate application url 
     * 
     * @param  string  $path               
     * @param  boolean $return             
     * @param  boolean $use_forwarded_host 
     * @return string
     */
    public function url($path = null, $use_forwarded_host = true)
    {
        $s = $_SERVER;

        $ssl      = ( ! empty( $s['HTTPS'] ) && $s['HTTPS'] == 'on' );
        $sp       = strtolower( $s['SERVER_PROTOCOL'] );
        $protocol = substr( $sp, 0, strpos( $sp, '/' ) ) . ( ( $ssl ) ? 's' : '' );
        $port     = $s['SERVER_PORT'];
        $port     = ( ( ! $ssl && $port=='80' ) || ( $ssl && $port=='443' ) ) ? '' : ':'.$port;
        $host     = ( $use_forwarded_host && isset( $s['HTTP_X_FORWARDED_HOST'] ) ) ? $s['HTTP_X_FORWARDED_HOST'] : ( isset( $s['HTTP_HOST'] ) ? $s['HTTP_HOST'] : null );
        $host     = isset( $host ) ? $host : $s['SERVER_NAME'] . $port;

        $final    = '//' . $host.Application::conf('path.public').'/'.$path;

        return $final;
    }

    /**
     * Call child output method and cache it if cache activated;
     * Can be overloaded by engines to customize how the cache data
     *
     * @param misc $data
     */
    protected function preOutput($data)
    {
        if(!$this->cache()->isEnabled()) $this->output($data);
        else {            
            //use cache instead outputing and evaluating view script
            if($this->cache()->isValid()) include($this->cache()->getCacheFile());
            else {
                //cache and output current view script
                ob_start();
                $this->output($data);
                //if(is_writable($cache_file)) { //fail if file cache doesn't already 
                    $content = ob_get_contents();
                    //if($this->_cache_strip) $content = preg_replace('!\s+!', ' ', $content);
                    file_put_contents($this->cache()->getCacheFile(), $content);
                //}
                ob_get_flush();
            }           
        }        
    }
    
    /**
     * Access to cache object
     *
     * @return object Peak_View_Cache
     */
    public function cache()
    {
    	if(!is_object($this->_cache)) $this->_cache = new Cache();
    	return $this->_cache;
    }

}