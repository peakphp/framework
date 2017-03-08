<?php

namespace Peak\View\Render;

use Peak\View\Render;

/**
 * Peak View Render Engine: Virtual Layout
 * Work like Layout but without files
 */
class VirtualLayouts extends Render
{
    /**
     * Layout content
     * @var string
     */
    private $layout = null;
    
    /**
     * Script content
     * @var string
     */
    private $content = null;
    
    /**
     * Will force processVariable() to also remove unknown variables
     * @var bool
     */
    private $clean_all_unknown_vars = true;

    /**
     * Set layout content
     *
     * @param  string $name
     * @return $this
     */
    public function setLayout($layout)
    {        
        $this->layout = $layout;
        return $this;
    }

    /**
     * Set/Overwrite content
     *
     * @param  string $content
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Add content
     * 
     * @param  string $content
     * @return $this
     */
    public function addContent($content)
    {
        $this->content .= $content;
        return $this;
    }

    /**
     * Render virtual layout(s)
     * 
     * {CONTENT} tag inside layout will be replaced by $content
     *
     * @param  string $file
     * @param  string $path
     * @return string
     */
    public function render($file, $path = null)
    {
        //CONTROLLER FILE VIEW
        $this->scripts_file = $file;
        $this->scripts_path = $path;

        if (is_null($this->layout)) {
            $output = $this->content;
        } else {
            $output = str_ireplace('{CONTENT}', $this->content, $this->layout);
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
     * Turn true/false property $clean_all_unknown_vars
     *
     * @param $val bool
     */
    public function cleanUnknownVars($val)
    {
        $this->clean_all_unknown_vars = ($value === true) ? true : false;
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
        if (!empty($vars)) {
            $vars_names = [];
            foreach ($vars as $k => $v) {
                //remove arrays from view vars otherwise php will throw a notice about array to string conversion
                if (is_array($v) || is_object($v)) {
                    unset($vars[$k]);
                } else {
                    $vars_names[] = '{$'.$k.'}';
                }
            }
            $content = str_ireplace($vars_names, array_values($vars), $content);

            //remove unknown vars {$keys}
            if ($this->clean_all_unknown_vars) {
                $content = preg_replace('#\{\$(\w+)\}#i', '', $content);
            }
        }
        return $content;
    }
}
