<?php
/**
 * Generate application launcher (generally index.php)
 * 
 * @author  Francois Lajoie
 * @version $Id$
 */
class Peak_Codegen_Index extends Peak_Codegen
{
	
	public $public_root = '';
	public $library_root = '';
	public $application_root = '';
	public $application_config = '';
	public $peakboot_filepath = 'Peak/boot.php';
	
    
    /**
     * Generate controller code
     */
	public function generate()
	{
		
$data = '';
$data = '
/**
 * REQUIRED CONSTANTS
 * Hint: *_ROOT constants reflect the relative path from the public folder (the folder where this file is located)
 */
define(\'PUBLIC_ROOT\',        \''.$this->public_root.'\');
define(\'LIBRARY_ROOT\',       \''.$this->library_root.'\');
define(\'APPLICATION_ROOT\',   \''.$this->application_root.'\');
define(\'APPLICATION_CONFIG\', \''.$this->application_config.'\');

/**
 * OPTIONNAL CONSTANTS
 * Hint: This can be setted as well in .htaccess
 */
//define(\'APPLICATION_ENV\',  \'development\');

/**
 * BOOT Peak
 */
include \''.$this->peakboot_filepath.'\';

/**
 * LANCH App
 */
try {
    $app = new Peak_Application();
    $app->run()->render();
}
catch (Exception $e) {

    $app->front->errorDispatch();
    $app->render();
}';

			return $data;
	}
	
}