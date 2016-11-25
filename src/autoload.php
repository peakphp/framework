<?php
/**
 * Peak SPL autoload
 */
//nullify any existing autoloads
spl_autoload_register(null, false);

//specify extensions that may be loaded
spl_autoload_extensions('.php');


spl_autoload_register(function($cn) {
    $file = LIBRARY_ABSPATH.'/Vendors/'._autoloadClass2File($cn);
    //if(!strstr('Zend', $file)) return;
    if(!file_exists($file)) return false;
    include $file;
});

function _autoloadClass2File($cn)
{
    return str_replace('_','/',$cn).'.php';
}