<?php

/**
 * Manage Application Views Themes folder(s)
 * 
 * @desc     Themes folder are /layout, /partials, /scripts, /cache
 *           By default they are in your application /views folder.
 *           If you set a theme name, then application folder /views/themes/[name] will be used
 * 
 * @author   Francois Lajoie
 * @version  $Id$
 */
class Peak_View_Theme
{
	
    /**
     * theme folde name
     * @var string
     */
    private $_theme_folder = null;

    /**
     * __construct()
     *
     * @param string $name 
     * @see setFolder()
     */
    public function __construct($name = null)
    {
    	if(isset($name)) $this->setFolder($name);
    }
    
    /**
     * Use views themes folder ( views/themes/[$name]/ ).
     * If $name is null, application /views/ folder will be used as views themes folder
     *
     * @param string|null $name
     */
    public function setFolder($name)
    {
    	$config = Peak_Registry::o()->config->getVars();

    	if(is_null($name))
    	{
    		$config['path']['views_themes']   = $config['path']['views'];
    		$config['path']['theme']          = $config->views_themes_path;
    		$config['path']['theme_scripts']  = $config->theme_path.'/scripts';
    		$config['path']['theme_partials'] = $config->theme_path.'/partials';
    		$config['path']['theme_layouts']  = $config->theme_path.'/layouts';
    		$config['path']['theme_cache']    = $config->theme_path.'/cache';

    	}
    	elseif(!$this->exists($name)) {
    		throw new Peak_View_Exception('ERR_VIEW_THEME_NOT_FOUND', $name);
    	}
    	else {

    		$config['path']['views_themes']   = $config['path']['views'].'/themes';
    		$config['path']['theme']          = $config['path']['views_themes'].'/'.$name;
    		$config['path']['theme_scripts']  = $config['path']['theme'] .'/scripts';
    		$config['path']['theme_partials'] = $config['path']['theme'] .'/partials';
    		$config['path']['theme_layouts']  = $config['path']['theme'] .'/layouts';
    		$config['path']['theme_cache']    = $config['path']['theme'] .'/cache';
    	}
    	
    	Peak_Registry::o()->config->setVars($config);

    	$this->_theme_folder = $name;
    }
    
    /**
     * Check if theme folder exists
     *
     * @param  string $name
     * @return bool
     */
    public function exists($name)
    {
    	return is_dir(Peak_Registry::o()->config->path['views'].'/themes/'.$name);
    }
}