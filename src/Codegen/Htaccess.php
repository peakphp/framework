<?php
/**
 * Generate .htaccess
 * 
 * @author  Francois Lajoie
 * @version $Id$
 */
class Peak_Codegen_Htaccess extends Peak_Codegen
{
	
	public $env = '';
    public $file = 'index.php';
	
    
    /**
     * Generate controller code
     */
	public function generate()
	{
		
$data = '';

if(!empty($this->env)) {
	$data .= 'SetEnv APPLICATION_ENV '.$this->env."\n";
}
$data .= 
'RewriteEngine On

## never rewrite for existing files, directories and links

RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-l

## rewrite everything else to '.$this->file.'

RewriteRule !\.(js|ico|gif|jpg|png|css)$ '.$this->file;
			return $data;
	}
	
}