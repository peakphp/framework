<?php

namespace Peak\Config\Type;

use Peak\Common\Traits\ArrayMergeRecursiveDistinct;
use Peak\Config\Loader;
use \Exception;

class IniLoader extends Loader
{
    use ArrayMergeRecursiveDistinct;

    /**
     * Constructor
     *
     * @param $config
     */
    public function __construct($config)
    {
        $this->config = $config;
        $this->loadFile($config);
    }

    /**
     * Parse ini from file
     *
     * @see load()
     */
    public function loadFile($file)
    {
        if (!file_exists($file)) {
            throw new Exception(__CLASS__.': file "'.$file.'" not found');
        }

        $ini = parse_ini_file($file, true);

        //parse_ini_file() can return false in case of error
        //but this can mean also that the file is only empty,
        //so we don't want to throw a exception in this case
        if ($ini === false && (trim(file_get_contents($file)) !== '')) {
            throw new Exception(__CLASS__.': syntax error(s) in your configuration file');
        }

        return $this->load($ini);
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
    public function load($ini)
    {
        // fail if there was an error while processing the specified ini file
        if ($ini === false) {
            return false;
        }

        // reset the result array
        $this->content = [];

        // loop through each section
        foreach ($ini as $section => $contents) {
            $this->processSection($section, $contents);
        }
    }

    /**
     * Process contents of the specified section
     *
     * @param  string $section Section name
     * @param  array $contents Section contents
     * @throws Exception
     */
    private function processSection($section, array $contents)
    {
        // the section does not extend another section
        if (stripos($section, ':') === false) {
            $this->content[$section] = $this->processSectionContents($contents);
            return;
        }

        // section extends another section
        // extract section names
        list($ext_target, $ext_source) = explode(':', $section);
        $ext_target = trim($ext_target);
        $ext_source = trim($ext_source);

        // check if the extended section exists
        if (!isset($this->content[$ext_source])) {
            throw new Exception(__CLASS__.': Unable to extend section ' . $ext_source . ', section not found');
        }

        // process section contents
        $this->content[$ext_target] = $this->processSectionContents($contents);

        // merge the new section with the existing section values
        $this->content[$ext_target] = $this->arrayMergeRecursiveDistinct(
            $this->content[$ext_source],
            $this->content[$ext_target]
        );
    }

    /**
     * Process contents of a section
     *
     * @param  array $contents Section contents
     * @return array
     */
    private function processSectionContents(array $contents)
    {
        $result = [];

        // loop through each line and convert it to an array
        foreach ($contents as $path => $value) {
            // convert all a.b.c.d to multi-dimensional arrays
            $process = $this->processContentEntry($path, $value);
            // merge the current line with all previous ones
            $result = $this->arrayMergeRecursiveDistinct($result, $process);
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
    private function processContentEntry($path, $value)
    {
        $pos = strpos($path, '.');

        if ($pos === false) {
            return [
                $path => $value
            ];
        }

        $key = substr($path, 0, $pos);
        $path = substr($path, $pos + 1);

        return [
            $key => $this->processContentEntry($path, $value)
        ];
    }
}
