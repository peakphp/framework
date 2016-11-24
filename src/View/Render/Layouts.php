<?php

/**
 * Peak View Render Engine: Layouts
 * 
 * @author   Francois Lajoie
 * @version  $Id$
 */
class Peak_View_Render_Layouts extends Peak_View_Render
{
        
    protected $_layout_file;   //current layout filename       

       
    /**
     * Set layout filename to render
     *
     * @param string $layout
     */
    public function useLayout($layout)
    {
        if($this->isLayout($layout.'.php')) $this->_layout_file = $layout.'.php';
    }
    
    /**
     * Verify if layout exists
     *
     * @param  string $name
     * @return bool
     */
    public function isLayout($name)
    {
    	return (file_exists(Peak_Core::getPath('theme_layouts').'/'.$name)) ? true : false;
    }

    /**
     * Desactivate layout
     * No layout means only the controller action view file is rendered
     */
    public function noLayout()
    {
        $this->_layout_file = null;
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
        // default path, no path submitted
        if(!isset($path)) {
            $path = Peak_Core::getPath('theme');
            $no_cache = true;
        }
        else $is_scripts_path = true;
        
        // absolute file path to render     
        $filepath = $path.'/'.$file;

        // throw the most reliable exception depending on submitted arguments to this method
        if(!file_exists($filepath)) {         
            if(isset($is_scripts_path)) {
                $filepath = Peak_Registry::o()->app->front->controller->getTitle() .'/'. basename($filepath);
                throw new Peak_View_Exception('ERR_VIEW_SCRIPT_NOT_FOUND', $filepath);
            }
            else {
                $filepath = str_replace($path, '', $filepath);
                throw new Peak_View_Exception('ERR_VIEW_FILE_NOT_FOUND', $filepath);
            }
        }
                     
        // render the layout if is set
        if((isset($this->_layout_file)) && ($this->isLayout($this->_layout_file))) {
            $filepath = Peak_Core::getPath('theme_layouts').'/'.$this->_layout_file;
            $this->scripts_file = $file;
            $this->scripts_path = $path;
        }

        if(isset($no_cache)) $this->output($filepath);
        else $this->preOutput($filepath);
    }
    
     
    /**
     * Output the main layout
     *
     * @param string $viewfile
     */
    protected function output($layout)
    {
        // remove layout
        // so we can use render() to include a partial file inside view scripts
        $this->noLayout();

        // include controller action view with or without partials groups
        include($layout);     
    }
    
    /**
     * Output Controller view content in layout
     * @example in your layout page, use $this->layoutContent() to display where controller action view should be displayed
     */
    public function layoutContent()
    {
        include($this->scripts_path.'/'.$this->scripts_file);
    }
        
}