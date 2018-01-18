<?php

namespace Peak\Bedrock\View\Helper;

use Peak\Bedrock\Application;
use Peak\Bedrock\View\Helper;

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
        echo '<pre class="peak_debug_tree">';
        foreach (Application::container()->getInstances() as $obj) {
            print_r($obj);
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
        $kernel = Application::kernel();
        $cfile_name = $kernel->front->controller->name;
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
        $kernel = Application::kernel();
        $sfile_name = $kernel->front->controller->file;
        $sfile = $kernel->front->controller->path.'/'.$sfile_name;
        
        if (file_exists($sfile)) {
            $sfile_content = file_get_contents($sfile);
            return $sfile_content;
        }
        return false;
    }
    
    /**
     * Get include files separated by category with additional info
     *
     * @return array
     */
    public function getFiles()
    {
        $temp = get_included_files();
        $files = [
            'app' => [],
            'total_size' => 0,
            'basepath' => '',
        ];
        foreach ($temp as $file) {
            $files['total_size'] += filesize($file);
            $file = str_replace(['\\','//'], '/', $file);
            $files['app'][] = $file;
        }
        natsort($files['app']);

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
        $unit = ['b','kb','mb','gb','tb','pb'];
        return round($size/pow(1024, ($i=floor(log($size, 1024)))), 4).' '.$unit[$i];
    }
}
