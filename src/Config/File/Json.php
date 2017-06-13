<?php

namespace Peak\Config\File;

use Peak\Config\DotNotation;
use \Exception;

/**
 * Peak_Config_Json
 *
 * Takes a JSON encoded file/string and converts it into a PHP variable.
 */
class Json extends DotNotation
{
    /**
     * Allow comments in json data
     * @var boolean
     */
    protected $_allow_comments = false;

    /**
     * Loaded file path by loadFile()
     * @var string
     */
    protected $_loaded_file;

    /**
     * Load file on class construct
     *
     * @see loadFile()
     */
    public function __construct($file = null, $allow_comments = false)
    {
        $this->_allow_comments = $allow_comments;
        if (isset($file)) {
            $this->loadFile($file);
        }
    }
    
    /**
     * Load json file array
     *
     * @param  string $file
     * @return array
     */
    public function loadFile($file)
    {
        if (!file_exists($file)) {
            throw new Exception(__CLASS__.': file "'.$file.'" not found');
        } else {
            $this->_loaded_file = $file;
            $content = file_get_contents($file);
            return $this->loadString($content);
        }
    }
    
    /**
     * Load json content
     *
     * @param  string $data
     * @return array
     */
    public function loadString($data)
    {
        // remove comments before decoding
        if ($this->_allow_comments === true) {
            $data = preg_replace("#(/\*([^*]|[\r\n]|(\*+([^*/]|[\r\n])))*\*+/)|([\s\t](//).*)#", '', $data);
        }

        $this->items = json_decode($data, true);
        $this->jsonError();
        return $this->items;
    }

    /**
     * Load json url
     *
     * @param  string      $url
     * @param  array|null  $post_data post data if specified
     * @return false|array return false in case url cant be reach
     */
    public function loadUrl($url, $post_data = null)
    {
        if (!function_exists('curl_init')) {
            throw new Exception(__CLASS__.'::loadUrl() need CURL php extension');
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);

        // post data
        if (is_array($post_data) && !empty($post_data)) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
        }

        // receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        curl_close($ch);

        if ($response !== false) {
            return $this->loadString($response);
        }
        return false;
    }
    
    /**
     * Get last json error if exists
     */
    private function jsonError()
    {
        switch (json_last_error()) {
            case JSON_ERROR_DEPTH:
                $e =  'Maximum stack depth exceeded';
                break;
            case JSON_ERROR_CTRL_CHAR:
                $e = 'Unexpected control character found';
                break;
            case JSON_ERROR_SYNTAX:
                $e = 'Syntax error, malformed JSON';
                break;
            case JSON_ERROR_STATE_MISMATCH:
                $e = 'Invalid or malformed JSON';
                break;
            case JSON_ERROR_UTF8:
                $e = 'Malformed UTF-8 characters, possibly incorrectly encoded'; //PHP 5 >= 5.3.3
                break;
        }

        if (isset($e)) {
            throw new Exception(__CLASS__.': '.$e);
        }
    }

    /**
     * Enable File write persistence. At each end of script, file data will be written to a file
     * using php register_shutdown_function() and class export2file()
     *
     * @param  string|null $filepath if null, $_loaded_file is used instead
     */
    public function enablePersistence($filepath = null)
    {
        if (!isset($filepath)) {
            $filepath = $this->_loaded_file;
        }

        register_shutdown_function(array($this, 'export2file'), $filepath);
    }
    
    /**
     * Write json to file
     *
     * @param  string|null $filepath if null, $_loaded_file is used instead
     */
    public function export2file($filepath = null)
    {
        if (!isset($filepath)) {
            if (!empty($this->_loaded_file)) {
                $filepath = $this->_loaded_file;
            } else {
                throw new Exception(__CLASS__.': No file specified for export');
            }
        }

        $data = $this->items;

        $result = file_put_contents($filepath, json_encode($data));
        if ($result === false) {
            throw new Exception(__CLASS__.': Fail to write file: '.$filepath);
        }
    }
}
