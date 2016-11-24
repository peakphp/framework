<?php
/**
 * Debug array display
 *
 * @author  Francois Lajoie
 * @version $Id: debug.php 319 2011-03-12 17:57:48Z snake386@hotmail.com $
 */
class Peak_View_Helper_Debug
{   
   
    /**
     * Print registry object list
     */
    public function registry()
    {
    	$object_list = Peak_Registry::getObjectList();
    	echo '<pre class="peak_debug_tree">';
    	foreach($object_list as $obj) {
    		
    		print_r(Peak_Registry::o()->$obj);
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
		$app = Peak_Registry::o()->app;
		$cfile_name = $app->front->controller->name;
		$cfile = Peak_Core::getPath('controllers').'/'.$cfile_name.'.php';
		if(file_exists($cfile)) {
			$cfile_content = file_get_contents($cfile);
			return $cfile_content;
		}
		else return false;
	}
	
	/**
	 * Get current script view content
	 *
	 * @return string/false
	 */
	public function getScriptSource()
	{
		$app = Peak_Registry::o()->app;
		$sfile_name = $app->front->controller->file;
		$sfile = $app->front->controller->path.'/'.$sfile_name;
		
		if(file_exists($sfile)) {
			$sfile_content = file_get_contents($sfile);
			return $sfile_content;
		}
		else return false;
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
		foreach($temp as $file) {
			$total_size += filesize($file);
			$file = str_replace('\\','/',$file);
			if(stristr($file, $library_path) !== false) $files['peak'][] = $file;
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