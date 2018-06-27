<?php

declare(strict_types=1);

namespace Peak\Config\Processor;

use Peak\Common\Traits\ArrayMergeRecursiveDistinct;
use Peak\Config\Exception\ProcessorException;

class IniProcessor implements ProcessorInterface
{
    use ArrayMergeRecursiveDistinct;

    /**
     * @var array
     */
    private $content;

    /**
     * @throws ProcessorException
     */
    public function process($data): array
    {
        $this->load($data);
        return $this->content;
    }

    /**
     * Loads in the ini file specified in filename, and returns the settings in
     * it as an associative multi-dimensional array
     *
     * @param  string  $data             Parsed content by php function parse_ini_*
     * @param  boolean $process_sections By setting the process_sections parameter to TRUE,
     *                                   you get a multidimensional array, with the section
     *                                   names and settings included. The default for
     *                                   process_sections is FALSE
     * @param  string $section_name      Specific section name to extract upon processing
     * @throws ProcessorException
     */
    public function load($data)
    {
        // we silence error(s) so we can catch them and throw a proper exception after
        $data = @parse_ini_string($data, true);

        // fail if there was an error while processing the specified ini file
        if ($data === false) {
            $error = error_get_last();
            throw new ProcessorException(__CLASS__.' fail to parse data: '.$error['message']);
        }

        // reset the result array
        $this->content = [];

        // loop through each section
        foreach ($data as $section => $contents) {
            $this->processSection($section, $contents);
        }
    }

    /**
     * Process contents of the specified section
     *
     * @param  string $section Section name
     * @param  array $contents Section contents
     * @throws ProcessorException
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
            throw new ProcessorException(__CLASS__.': Unable to extend section ' . $ext_source . ', section not found');
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
