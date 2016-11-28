<?php
namespace Peak\View\Render;

use Peak\Registry;
use Peak\View\Render;

/**
 * Peak View Render Engine: Json
 * 
 * Output view vars as json 
 */
class Json extends Render
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

        $viewvars = Registry::o()->view->getVars();

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