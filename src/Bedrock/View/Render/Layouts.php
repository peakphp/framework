<?php

namespace Peak\Bedrock\View\Render;

use \Exception;
use Peak\Bedrock\Application;
use Peak\Bedrock\View\Render;

/**
 * Peak View Render Engine: Layouts
 */
class Layouts extends Render
{
    /**
     * Current layout filename
     * @var string
     */
    protected $layout_file;
       
    /**
     * Set layout filename to render
     *
     * @param string $layout
     */
    public function useLayout($layout)
    {
        if ($this->isLayout($layout.'.php')) {
            $this->layout_file = $layout.'.php';
        }
    }
    
    /**
     * Verify if layout exists
     *
     * @param  string $name
     * @return bool
     */
    public function isLayout($name)
    {
        return (file_exists(Application::conf('path.apptree.views_layouts').'/'.$name)) ? true : false;
    }

    /**
     * Desactivate layout
     * No layout means only the controller action view file is rendered
     */
    public function noLayout()
    {
        $this->layout_file = null;
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
        if (!isset($path)) {
            $path = Application::conf('path.apptree.views');
            $no_cache = true;
        } else {
            $is_scripts_path = true;
        }

        // absolute file path to render
        $filepath = $path.'/'.$file;

        // throw the most reliable exception depending on submitted arguments to this method
        if (!file_exists($filepath)) {
            if (isset($is_scripts_path)) {
                $kernel = Application::kernel();
                $filepath = $kernel->front->controller->getTitle() .'/'. basename($filepath);
                throw new Exception('View script file '.basename($filepath).' not found');
            } else {
                $filepath = str_replace($path, '', $filepath);
                throw new Exception('View file '.basename($filepath).' not found');
            }
        }
                     
        // render the layout if is set
        if ((isset($this->layout_file)) && ($this->isLayout($this->layout_file))) {
            $filepath = Application::conf('path.apptree.views_layouts').'/'.$this->layout_file;
            $this->scripts_file = $file;
            $this->scripts_path = $path;
        }

        if (isset($no_cache)) {
            $this->output($filepath);
        } else {
            $this->preOutput($filepath);
        }
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
     * In your layout page, use $this->layoutContent() to display where controller action view should be displayed
     */
    public function layoutContent()
    {
        include($this->scripts_path.'/'.$this->scripts_file);
    }
}
