<?php
/**
 * Peak View Render Engine: Json
 * 
 * @desc     Output view vars as json 
 * 
 * @author   Francois Lajoie
 * @version  $Id$ 
 */
class Peak_View_Render_Json extends Peak_View_Render
{
                     
    /**
     * Render view(s)
     *
     * @param  string $file
     * @param  string $path
     * @return array/string
     */
    public function render($file, $path = null)
    {       
        //CONTROLLER FILE VIEW       
        $this->scripts_file = $file;
        $this->scripts_path = $path;

        $viewvars = Peak_Registry::o()->view->getVars();

        header('Content-Type: application/json');
        
        $json = json_encode($viewvars);

        $this->preOutput($json);
    }
    
    /**
     * Output Json
     *
     * @param string $json
     */
    protected function output($json)
    {
        echo $json;    
    }
    
}