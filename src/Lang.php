<?php
/**
 * Simple lang translator based on php array
 * 
 * @author   Francois Lajoie
 * @version  $Id$
 */
class Peak_Lang
{     
    /**
     * Language abbreviation
     * @var string
     */
    protected $_lang;

    /**
     * List of language file loaded
     * @var array
     */
    protected $_loaded_files = array();

    /**
     * Array of translation
     * @var array
     */
    public $translations = array();

    /**
     * Set lang abbr if specified
     *
     * @param string $lang
     */
    public function __construct($lang = null)
    {
        if(isset($lang)) $this->setLang($lang);
    }

    /**
     * Set the language abbr
     * 
     * @param string $lang_abbr
     */
    public function setLang($lang_abbr)
    {
        $this->_lang = trim(strtolower($lang_abbr));
        return $this;
    }

    /**
     * Return current lang abbr ($_lang)
     * 
     * @return string
     */
    public function getLang()
    {
        return $this->_lang;
    }
    
    /**
     * Load language file directly. Usefull when class needed as standalone
     *
     * @param string $filepath
     * @param bool   $return    if false, file content won't be added to $this->translatation
     */
    public function loadFile($filepath, $return = false)
    {       
        if(empty($this->_lang)) {
            throw new Exception(__CLASS__.': You must set the language abbreviation before loading a translation file.');
        }

        $filepath = $this->_exists($filepath);

        // file exists
        if($filepath !== false) {

            $tmp = include $filepath;
            if(!is_array($tmp)) $tmp = array();

            $this->_loaded_files[] = $filepath;

            if($return) return $tmp;
            else $this->translations = $tmp;
        }
        elseif($return) return array();
    }

    /**
     * Add file(s) to the current translations var
     * 
     * @param string|array $files
     */
    public function addFiles($files)
    {
        if(is_array($files)) {
            if(!empty($files)) {
                foreach($files as $f) {
                    
                    $tmp = $this->loadFile($f, true);
                    $this->translations = array_merge($this->translations, $tmp);

                }
            }
        }
    }

    /**
     * Return loaded translation files
     * 
     * @return array
     */
    public function getLoadedFiles()
    {
        return $this->_loaded_files;
    }

    /**
     * Return false if file don't exists, return complete filepath if exists
     * 
     * @param  string      $file 
     * @return bool|string       
     */
    protected function _exists($file)
    {
        if(file_exists($file)) {
            return $file;
        }

        // relative path to the current application if context apply
        elseif(defined('APPLICATION_ABSPATH')) {
            $filepath = APPLICATION_ABSPATH.'/'.$file;
            if(file_exists($filepath)) {
                return $filepath;
            }
        }
        
        return false;
    }

    /**
     * Translate text
     *
     * @param  string $item
     * @param  string $replaces text replacements
     * @return string
     */
    public function translate($item, $replaces = null)
    {           
        $tr = (isset($this->translations[$item])) ? $this->translations[$item] : $item;

        if(isset($replaces)) {
            if(is_array($replaces)) $tr = vsprintf($tr, $replaces);
            else $tr = sprintf($tr, $replaces);         
        }
        
        return $tr;
    }

}

/**
 * Echo an translation
 *
 * @param see method translate() of Peak_Lang for info on params
 */
function __($text, $replaces = null, $func = null)
{
    if(Peak_Registry::o()->lang instanceof Peak_Lang)   {       
        return Peak_Registry::o()->lang->translate((string)$text, $replaces, $func);
    }
    else return $text;
}

/**
 * Echo the result of __() function
 */
function _e($text,$replaces = null,$func = null) { echo __($text,$replaces,$func); }