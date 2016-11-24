<?php
/**
 * Peak View Render Engine: Virtual
 * 
 * @desc     Output virtual groups view vars
 * 
 * @author   Francois Lajoie
 * @version  $Id$
 */
class Peak_View_Render_Virtual extends Peak_View_Render
{

    private $_virtual = array();
    
    /**
     * Add virtual group
     *
     * @param string $name
     */
    public function addGroup($name)
    {        
        if(!array_key_exists($name,$this->_virtual)) $this->_virtual[$name] = '';
    }
    
    /**
     * Delete virtual group
     *
     * @param string $name
     */
    public function delGroup($name)
    {
        unset($this->_virtual[$name]);
    }
    
    /**
     * Add virtual group data
     *
     * @param string $group_name
     * @param string $data
     * @param bool   $overwrite set to true if you want to overwrite group actual data by $data
     */
    public function add($group_name, $data, $overwrite = false)
    {
        $this->addGroup($group_name);
        if(!$overwrite) $this->_virtual[$group_name] .= $data;
        else $this->_virtual[$group_name] = $data;      
    }
    
    /**
     * Render virtual group(s)
     *
     * @param string $file
     * @param string $path
     * @return array/string
     */
    public function render($file, $path = null)
    {       
        //CONTROLLER FILE VIEW       
        $this->scripts_file = $file;
        $this->scripts_path = $path;

        $output = '';
        foreach($this->_virtual as $group => $content) $output .= $content;

        $this->output($output);
    }
    
    /**
     * Output virtual group rendering result
     *
     * @param string $data
     */
    private function output($data)
    {
        echo $data;    
    }
    
}