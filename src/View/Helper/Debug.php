<?php

namespace Peak\View\Helper;

use Peak\Application;
use Peak\Registry;
use Peak\View\Helper;

/**
 * Debug array display
 */
class Debug extends Helper
{
    /**
     * Print registry object list
     */
    public function registry()
    {
        $object_list = Registry::getObjectList();
        echo '<pre class="peak_debug_tree">';
        foreach ($object_list as $obj) {
            print_r(Registry::o()->$obj);
        }
        echo '</pre>';
    }
    
    /**
     * Get current controller content
     *
     * @return string/false
     */
    public function getControllerSource()
    {
        $app = Registry::o()->app;
        $cfile_name = $app->front->controller->name;
        $cfile = Application::conf('path.apptree.controllers').'/'.$cfile_name.'.php';
        if (file_exists($cfile)) {
            $cfile_content = file_get_contents($cfile);
            return $cfile_content;
        }
        return false;
    }
    
    /**
     * Get current script view content
     *
     * @return string/false
     */
    public function getScriptSource()
    {
        $app = Registry::o()->app;
        $sfile_name = $app->front->controller->file;
        $sfile = $app->front->controller->path.'/'.$sfile_name;
        
        if (file_exists($sfile)) {
            $sfile_content = file_get_contents($sfile);
            return $sfile_content;
        }
        return false;
    }
    
    /**
     * Get include files separated by category with additionnal info
     *
     * @return array
     */
    public function getFiles()
    {
        $temp = get_included_files();
        $files = array();
        $total_size = 0;
        $library_path = str_replace(array('\\','//'),'/',realpath(LIBRARY_ABSPATH));
        foreach ($temp as $file) {
            $total_size += filesize($file);
            $file = str_replace('\\','/',$file);
            if (stristr($file, $library_path) !== false) $files['peak'][] = $file;
            else $files['app'][] = $file;
        }
        $files['total_size'] = $total_size;
        
        return $files;
    }
    
    /**
     * Get Memory usage
     *
     * @return string
     */
    public function getMemoryUsage()
    {
        $size = memory_get_peak_usage(true);
        $unit = array('b','kb','mb','gb','tb','pb');
        return @round($size/pow(1024,($i=floor(log($size,1024)))),4).' '.$unit[$i];
    }
}
