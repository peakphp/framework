<?php

namespace Peak\Bedrock\View\Render;

use Peak\Bedrock\Application;
use Peak\Bedrock\View\Render;
use \Exception;

/**
 * Peak View Render Engine: Partials
 *
 * support groups alias from theme.ini
 * if no groups, only the controller action view file will be render
 */
class Partials extends Render
{

    protected $groups;      //available partials groups
    protected $group;       //current partials group file to render
    protected $group_name;  //current partials group name

    /**
     * Load Partials engine with groups alias
     *
     * @param array $groups
     */
    public function __construct($groups = null)
    {
        $this->groups = $groups;
        if (isset($this->groups['default'])) {
            $this->useGroup('default');
        }
    }

    /**
     * Submit array of files or point to $groups array keyname for rendering
     *
     * @example useGroup( array('header.php','[CONTENT]','footer.php') )
     * @example useGroup('content_left') will push $this->options['layouts'][$layout] array to $this->layout
     *
     * @param array|string $array
     */
    public function useGroup($group)
    {
        if (is_array($group)) {
            $this->group = $group;
            $this->group_name = 'custom';
        } elseif (isset($this->groups[$group])) {
            $this->group = $this->groups[$group];
            $this->group_name = $group;
        }
    }

    /**
     * Erase current partials rendering group
     * No group means only the controller action view file is render
     */
    public function noGroup()
    {
        $this->group = null;
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
        
        if (!isset($path)) {
            $this->scripts_path = Application::conf('path.apptree.theme_partials');
            $path = Application::conf('path.apptree.theme_partials');
            $no_cache = true;
        }
        
        $filepath = $path.'/'.$file;

        if (!file_exists($filepath)) {
            $kernel = Application::kernel();
            $filepath = $kernel->front->controller->title .'/'. basename($filepath);
            throw new Exception(__CLASS__.': view script ['.$filepath.'] not found');
        }

        //Partials group FILES VIEW IF EXISTS
        if (is_array($this->group)) {
            $group_filespath = [];
            
            foreach ($this->group as $theme_partial) {
                if ($theme_partial !== '[CONTENT]') {
                    if (basename($theme_partial) === $theme_partial) {
                        if (file_exists(Application::conf('path.apptree.theme_partials').'/'.$theme_partial)) {
                            $group_filespath[] = Application::conf('path.theme_partials').'/'.$theme_partial;
                        }
                    } elseif (file_exists($theme_partial)) {
                        $group_filespath[] = $theme_partial;
                    }
                } else {
                    $group_filespath[] = $filepath;
                }
            }
            $this->preOutput($group_filespath);
        } else {
            if (isset($no_cache)) {
                $this->output($filepath);
            } else {
                $this->preOutput($filepath);
            }
        }
    }

    protected function output($viewfiles)
    {
        // remove partials group for Peak_View_Render_Partials
        // so we can use render() to include a single partial file without group inside view scripts
        $this->noGroup();

        // include controller action view with or without partials groups
        if (is_array($viewfiles)) {
            foreach ($viewfiles as $file) {
                include($file);
            }
        } else {
            include($viewfiles);
        }
    }
}
