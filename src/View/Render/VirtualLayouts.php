<?php
/**
 * Peak View Render Engine: Virtual Layout
 * 
 * @desc     Work like Layout but without files
 * 
 * @author   Francois Lajoie
 * @version  $Id$
 */
class Peak_View_Render_VirtualLayouts extends Peak_View_Render
{

    /**
     * Layout content
     * @var string
     */
    private $_layout = null;
    
    /**
     * Script content
     * @var string
     */
    private $_content = null;
    
    /**
     * Will force processVariable() to also remove unknown variables
     * @var bool
     */
    private $_clean_all_unknown_vars = true;
    
    /**
     * Set layout content
     *
     * @param string $name
     */
    public function setLayout($layout)
    {        
        $this->_layout = $layout;
    }

    /**
     * Set script content
     *
     * @param string $content
     * @param bool   $overwrite if false, content will be added at the end
     */
    public function setContent($content, $overwrite = false)
    {
        if($overwrite) $this->_content = $content;
        else $this->_content .= $content;
    }
    
    /**
     * Render virtual layout(s)
     * 
     * {CONTENT} tag inside layout will be replaced by $_content
     *
     * @param  string $file
     * @param  string $path
     * @return string
     */
    public function render($file,$path = null)
    {       
        //CONTROLLER FILE VIEW       
        $this->scripts_file = $file;
        $this->scripts_path = $path;

        if(is_null($this->_layout)) {
            $output = $this->_content;
        }
        else {
            $output = str_ireplace('{CONTENT}', $this->_content, $this->_layout);
        }
        
        $output = $this->_proccessVariables($output);

        $this->output($output);
    }
    
    /**
     * Output rendering result
     *
     * @param string $data
     */
    protected function output($data)
    {
        echo $data;
    }
    
    /**
     * Turn true/false property $_clean_all_unknown_vars
     *
     * @param $val bool
     */
    public function cleanUnknownVars($val)
    {
        $this->_clean_all_unknown_vars = ($value === true) ? true : false;
    }
    
    /**
     * Process all the variables
     *
     * @param  string $content
     * @return string
     */
    protected function _proccessVariables($content)
    {
        $vars = $this->getVars();
        if(!empty($vars)) {

            $vars_names = array();
            foreach($vars as $k => $v) {
                //remove arrays from view vars otherwise php will throw a notice about array to string conversion
                if(is_array($v) || is_object($v)) unset($vars[$k]); 
                else $vars_names[] = '{$'.$k.'}';
            }
            $content = str_ireplace($vars_names, array_values($vars), $content);
            
            //remove unknown vars {$keys}
            if($this->_clean_all_unknown_vars) {
                $content = preg_replace('#\{\$(\w+)\}#i', '', $content);
            }
        }
        return $content;
    }
    
}