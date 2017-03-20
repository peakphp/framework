<?php

namespace Peak\Config\File;

use \Exception;
use Peak\Config\DotNotation;

/**
 * Ini
 * 
 * This class allows you to:
 * - define multi-dimensional structures
 * - group configurations (e.g. production, development, testing, etc.) into separate sections (native INI sections)
 * - extend sections from one another
 * - override keys of extended sections in extending sections
 * 
 * @author  Original author: Andris at http://codeaid.net
 *          Thanks to him for letting me use and modify his class for the needs of the framework
 */
class Ini extends DotNotation
{
    /**
     * Load file on class construct
     *
     * @see loadFile()
     */
    public function __construct($file = null, $process_sections = false, $section_name = null)
    {
        if (isset($file)) {
            $this->loadFile($file, $process_sections, $section_name);
        }
    }
    
    /**
     * Parse ini from file
     *
     * @see _load()
     */
    public function loadFile($file, $process_sections = false, $section_name = null)
    {
        if (!file_exists($file)) {
            throw new Exception(__CLASS__.': file "'.$file.'" not found');
        }

        $ini = @parse_ini_file($file, $process_sections);
        
        //(php 5.2.7+) since parse_ini_file() can return false in case of error
        //but this can mean also that the file is only empty, so we don't want to throw a exception in this case
        
        if ((version_compare(PHP_VERSION, '5.2.7') >= 0) && ($ini == false)) {
            //check if the file just empty
            if (trim(file_get_contents($file)) !== '') {
                throw new Exception(__CLASS__.': syntax error(s) in your configuration file');
            }
        }
        
        return $this->_load($ini, $process_sections, $section_name);
    }
    
    /**
     * Parse ini from a string (PHP 5 >= 5.3.0)
     *
     * If you really need this under PHP 5 < 5.3.0, uncomment function
     * parse_ini_string() at the bottom of this file
     *
     * @see _load()
     */
    public function loadString($string, $process_sections = false, $section_name = null)
    {
        //PHP 5 >= 5.3.0
        $ini = parse_ini_string($string, $process_sections);
        return $this->_load($ini, $process_sections, $section_name);
    }
    
    /**
     * Loads in the ini file specified in filename, and returns the settings in
     * it as an associative multi-dimensional array
     *
     * @param  string  $ini              Parsed content by php function parse_ini_*
     * @param  boolean $process_sections By setting the process_sections parameter to TRUE,
     *                                   you get a multidimensional array, with the section
     *                                   names and settings included. The default for
     *                                   process_sections is FALSE
     * @param  string $section_name      Specific section name to extract upon processing
     * @throws Exception
     * @return array|boolean
     */
    public function _load($ini, $process_sections = false, $section_name = null)
    {
        // load the raw ini file 
        //$ini = parse_ini_string($data, $process_sections);

        // fail if there was an error while processing the specified ini file
        if ($ini === false) {
            return false;
        }

        // reset the result array
        $this->items = array();

        if ($process_sections === true) {
            // loop through each section
            foreach ($ini as $section => $contents) {
                $this->_processSection($section, $contents);
            }
        } else {
            // treat the whole ini file as a single section
            $this->items = $this->_processSectionContents($ini);
        }

        //  extract the required section if required
        if ($process_sections === true) {
            if ($section_name !== null) {
                // return the specified section contents if it exists
                if (isset($this->items[$section_name])) {
                    $this->items = $this->items[$section_name];
                } else {
                    throw new Exception(__CLASS__.': Section ' . $section_name . ' not found in the ini file');
                }
            }
        }

        // if no specific section is required, just return the whole result
        return $this->items;
    }

    /**
     * Process contents of the specified section
     *
     * @param  string $section Section name
     * @param  array $contents Section contents
     * @throws Exception
     */
    private function _processSection($section, array $contents)
    {
        // the section does not extend another section
        if (stripos($section, ':') === false) {
            $this->items[$section] = $this->_processSectionContents($contents);
        // section extends another section
        } else {
            // extract section names
            list($ext_target, $ext_source) = explode(':', $section);
            $ext_target = trim($ext_target);
            $ext_source = trim($ext_source);

            // check if the extended section exists
            if (!isset($this->items[$ext_source])) {
                throw new Exception(__CLASS__.': Unable to extend section ' . $ext_source . ', section not found');
            }

            // process section contents
            $this->items[$ext_target] = $this->_processSectionContents($contents);

            // merge the new section with the existing section values
            $this->items[$ext_target] = $this->_mergeRecursiveDistinct($this->items[$ext_source], $this->items[$ext_target]);
        }
    }

    /**
     * Process contents of a section
     *
     * @param  array $contents Section contents
     * @return array
     */
    private function _processSectionContents(array $contents)
    {
        $result = array();

        // loop through each line and convert it to an array
        foreach ($contents as $path => $value) {
            // convert all a.b.c.d to multi-dimensional arrays
            $process = $this->_processContentEntry($path, $value);
            // merge the current line with all previous ones
            $result = $this->_mergeRecursiveDistinct($result, $process);
        }
        
        return $result;
    }

    /**
     * Convert a.b.c.d paths to multi-dimensional arrays
     *
     * @param  string $path Current ini file's line's key
     * @param  mixed  $value Current ini file's line's value
     * @return array
     */
    private function _processContentEntry($path, $value)
    {
        $pos = strpos($path, '.');

        if ($pos === false)  return array($path => $value);

        $key = substr($path, 0, $pos);
        $path = substr($path, $pos + 1);

        return array($key => $this->_processContentEntry($path, $value));
    }
}
