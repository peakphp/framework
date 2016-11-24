<?php
/**
 * Peak_View_Cache
 * 
 * This object manage view cache and it is encapsuled inside Peak_View_Render
 * 
 * @author  Francois Lajoie
 * @version $Id$
 */
class Peak_View_Cache
{
	protected $_use_cache = false;     //use scripts view cache, false by default
	protected $_cache_expire;          //script cache expiration time
	protected $_cache_path;            //scripts view cache path. generate by enableCache()
	protected $_cache_id;              //current script view md5 key. generate by preOutput()
	protected $_cache_strip = false;   //will strip all repeating space caracters


	/**
     * Set cache folder
     */
	public function __construct()
	{
		$this->_cache_path = Peak_Core::getPath('theme_cache');
	}
	
	/**
	 * Get script file in Peak_View_Render
	 *
	 * @return string
	 */
	protected function getScriptFile()
	{
		return Peak_Registry::o()->view->engine()->_scripts_file;
	}
	
	/**
	 * Get script path in Peak_View_Render
	 *
	 * @return string
	 */
	protected function getScriptPath()
	{
		return Peak_Registry::o()->view->engine()->_scripts_path;
	}

	/**
     * Enable output caching. 
     * Avoid using in controllers actions that depends on $_GET, $_POST or any dynamic value for setting the view
     *
     * @param integer $time set cache expiration time(in seconds)
     */
	public function enable($time)
	{
		if(is_integer($time)) {
			$this->_use_cache = true;
			$this->_cache_expire = $time;
		}
	}

	/**
     * Desactivate output cache
     */
	public function disable()
	{
		$this->_use_cache = false;
	}
	
	/**
	 * Check if cache is enabled
	 *
	 * @return bool
	 */
	public function isEnabled()
	{
		return $this->_use_cache;
	}

	/**
     * Check if current view script file is cached/expired
     * Note: if $this->_cache_id is not set, this will generate a new id from $id params if set or
     * from the current controller file - path
     * Notes: Custom $id can lead to problems if used with controller redirection * need to be fix
     * 
     * @return bool
     */
	public function isValid($id = null)
	{
		if($this->_use_cache === false) return false;

		//when checking isValid without a custom id in controller action we generated a new id based
		//on controller name and action name. If id is null but, cache id is already generated, we use it.
		if(is_null($id)) {
			if(is_null($this->_cache_id)) {
				$this->genCacheId(Peak_Registry::o()->app->front->controller->getName(),
								  Peak_Registry::o()->app->front->controller->action);
			}
		}
		else $this->genCacheId(null, $id);

		$filepath = $this->getCacheFile();

		if(file_exists($this->getCacheFile())) {
			$file_date = filemtime($this->getCacheFile());
			$now = time();
			$delay = $now - $file_date;
			return ($delay >= $this->_cache_expire) ? false : true;
		}
		else return false;
	}

	/**
     * Generate md5 cache id from script view filename and path by default
     * Set a $path and $file to generate a new custom id.
     *
     * @param string $path
     * @param string $file
     */
	public function genCacheId($path = null, $file = null, $return = false)
	{
		//use current $this->_script_file and _script_path if no path/file scpecified
		if(!isset($path) && !isset($file)) $key = $this->getScriptPath().$this->getScriptFile();
		else $key = $path.$file;

		if(!$return) $this->_cache_id = hash('md5', $key);
		else return hash('md5', $key);
	}

	/**
     * Get current cached view script complete filepath
     *
     * @return string
     */
	public function getCacheFile()
	{
		return $this->_cache_path.'/'.$this->_cache_id.'.php';
	}

	/**
     * Enable/disable cache compression
     *
     * @param bool $status
     */
	public function enableStrip($status)
	{
		if(is_bool($status)) $this->_cache_strip = $status;
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
		if($this->isValid($id)) return true;
		else {
			ob_start();
			return false;
		}
	}

	/**
     * Close buffer of a cache block previously started by isCachedBlock()
     */
	public function blockEnd()
	{
		file_put_contents($this->getCacheFile(), preg_replace('!\s+!', ' ', ob_get_contents()));
		ob_get_flush();
	}

	/**
     * Get a custom cache block
     */
	public function getCacheBlock()
	{
		include($this->getCacheFile());
	}

	/**
     * Delete current cache file or custom cache file id
     *
     * @param string $id
     */
	public function deleteCache($id = null)
	{
		if(!isset($id)) $this->genCacheId();
		else $this->genCacheId('', $id);

		$file = $this->getCacheFile();
		if(file_exists($file)) unlink($file);
	}
}