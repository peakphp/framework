<?php
/**
 * Peak View Render Engine: Partials
 * 
 * @author   Francois Lajoie
 * @version  $Id$
 * 
 * support groups alias from theme.ini
 * if no groups, only the controller action view file will be render
 * 
 */
class Peak_View_Render_Partials extends Peak_View_Render
{   
    
    protected $_groups;      //available partials groups   
    protected $_group;       //current partials group file to render   
    protected $_group_name;  //current partials group name
   
    
    /**
     * Load Partials engine with groups alias 
     *
     * @param array $groups
     */
    public function __construct($groups = null)
    {
        $this->_groups = $groups;
        if(isset($this->_groups['default'])) $this->useGroup('default');        
    }
    
    /**
     * Submit array of files or point to $groups array keyname for rendering
     * 
     * @example useGroup( array('header.php','[CONTENT]','footer.php') )
     * @example useGroup('content_left') will push $this->options['layouts'][$layout] array to $this->layout
     *
     * @param array,string $array
     */
    public function useGroup($group)
    {
        if(is_array($group)) {
            $this->_group = $group;
            $this->_group_name = 'custom';
        }
        elseif(isset($this->_groups[$group])) {
            $this->_group = $this->_groups[$group];
            $this->_group_name = $group;
        }
    }

    /**
     * Erase current partials rendering group
     * No group means only the controller action view file is render
     */
    public function noGroup()
    {
        $this->_group = null;
    }
    
    
    /**
     * Render view(s)
     *
     * @param string $file
     * @param string $path
     * @return array/string
     */
    public function render($file, $path = null)
    {    
        //CONTROLLER FILE VIEW       
        $this->_scripts_file = $file;
        $this->_scripts_path = $path;
        
        if(!isset($path)) {
        	$this->scripts_path = Peak_Core::getPath('theme_partials');
        	$path = Peak_Core::getPath('theme_partials');
        	$no_cache = true;
        }
        
        $filepath = $path.'/'.$file;

        if(!file_exists($filepath)) {
            $filepath = Peak_Registry::o()->app->front->controller->title .'/'. basename($filepath);
            throw new Peak_View_Exception('ERR_VIEW_SCRIPT_NOT_FOUND', $filepath);        
        }
        
        //Partials group FILES VIEW IF EXISTS
        if(is_array($this->_group))
        {          
            $group_filespath = array();
            
            foreach($this->_group as $theme_partial) {
                if($theme_partial !== '[CONTENT]') {
                    if(basename($theme_partial) === $theme_partial) {
                        if(file_exists(Peak_Core::getPath('theme_partials').'/'.$theme_partial)) {
                        	$group_filespath[] = Peak_Core::getPath('theme_partials').'/'.$theme_partial;
                        }
                    }
                    elseif(file_exists($theme_partial)) $group_filespath[] = $theme_partial;
                }
                else $group_filespath[] = $filepath;
            }
            
            $this->preOutput($group_filespath);  
            
        }
        else {
        	if(isset($no_cache)) $this->output($filepath);
            else $this->preOutput($filepath);
        }
    }
    
    protected function output($viewfiles)
    {
        // remove partials group for Peak_View_Render_Partials
        // so we can use render() to include a single partial file without group inside view scripts
        $this->noGroup();  
        
        // include controller action view with or without partials groups
        if(is_array($viewfiles)) foreach($viewfiles as $file) include($file);
        else include($viewfiles);    
    }
    
}