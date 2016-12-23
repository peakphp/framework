<?php
namespace Peak\View;

/**
 * View helper - Http header
 */
class Header 
{

    /**
     * Header fields
     * @var array
     */
    protected $_header = [];

    /**
     * Additonnal content after
     * @var string
     */
    protected $_content = null;

    /**
     * Did we release header fields
     * @var boolean
     */
    protected $_released = false;

    /**
     * Hold stop release() method from outputting the stuff
     * @var boolean
     */
    protected $_hold = false;

    /**
     * List of http status codes
     * @var array
     */
    protected $_http_status_codes = [

        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => '(Unused)',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',
        419 => 'Authentication Timeout',
        420 => 'Enhance Your Calm',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        424 => 'Method Failure',
        425 => 'Unordered Collection',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        444 => 'No Response',
        449 => 'Retry With',
        450 => 'Blocked by Windows Parental Controls',
        451 => 'Unavailable For Legal Reasons',
        494 => 'Request Header Too Large',
        495 => 'Cert Error',
        496 => 'No Cert',
        497 => 'HTTP to HTTPS',
        499 => 'Client Closed Request',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        509 => 'Bandwidth Limit Exceeded',
        510 => 'Not Extended',
        511 => 'Network Authentication Required',
        598 => 'Network read timeout error',
        599 => 'Network connect timeout error'
    ];


    /**
     * "Release" header (simply call php header()) and content if any
     *
     * @param bool $die 
     */
    public function release($die = false)
    {
        if($this->_hold === true) return;

        if(!empty($this->_header) && !headers_sent() && $this->_released === false) {

            $this->_released = true;

            foreach($this->_header as $k => $field) {
                header($field);
            }
            if(!is_null($this)) {
                echo $this->_content;
            }

            if($die) die();
        }
    }

    /**
     * Hold the header releasing
     */
    public function holdOn()
    {
        $this->_hold = true;
        return $this;
    }

    /**
     * Hold the header releasing
     */
    public function holdOff()
    {
        $this->_hold = false;
        return $this;
    }

    /**
     * Set header field(s)
     * 
     * @param  string|array $field
     * @return object       $this
     */
    public function set($field)
    {
        if(!empty($field)) {

            if(!is_array($field)) {
                $this->_header[] = $field;
            }
            else {
                foreach($field as $f) {
                    $this->_header[] = $f;
                }
            }
        }
        return $this;
    }

    /**
     * Set content after header release
     *
     * @param  string $data
     * @return object $this
     */
    public function setContent($data)
    {
        $this->_content = $data;
        return $this;
    }   

    /**
     * Flush all headers and content
     * 
     * @return object  $this
     */
    public function reset()
    {
        $this->_content  = '';
        $this->_header[] = [];
        $this->_released = false;
        return $this;
    }

    /**
     * Get http status code text
     * 
     * @param  integer $code http status code
     * @return string
     */
    public function codeAsStr($code)
    {
        if(array_key_exists($code, $this->_http_status_codes)) {
            return $this->_http_status_codes[$code];
        }
        return;
    }

    /**
     * Set a valid http response code
     * 
     * @param  integer     $code
     * @param  bool|string $die   if not false, but a string instead of true, it will die with $die string as message
     * @param  string      $http
     *
     * @return object  $this
     */
    public function setCode($code = 202, $die = false, $http = 'HTTP/1.1')
    {
        if(array_key_exists($code,$this->_http_status_codes)) {
            $this->set($http.' '.$code.' '.$this->_http_status_codes[$code]);
        }

        if($die !== false) {
            $this->release();
            if($die === true) $die = null;
            die($die);
        }
        
        return $this;
    }

    /**
     * Force a browser to use fresh content (no caching)
     * 
     * @return object $this
     */
    public function noCache()
    {
        $h = [
            'Cache-Control: no-cache, must-revalidate',
            'Expires: Thu, 01 Jan 1970 00:00:00 GMT'
        ];
        return $this->set($h);
    }

    /**
     * Redirect to an url and halt the script
     * 
     * @param  string  $url  redirect url location
     * @param  integer $code
     */
    public function redirect($url, $code = 302)
    {
        $this->setCode($code);
        $this->set('Location: '.$url);
        $this->release(true);
    }

    /**
     * Set header to download a file
     * 
     * @param  string $fielpath  full path file
     * @param  string $mmtype    Represent Content-type
     * 
     * @return object $this
     */
    public function download($filepath, $mmtype = 'application/octet-stream')
    {
        if(file_exists($filepath)) {

            // dowwload header
            $h = [
                'Cache-Control: public, must-revalidate',
                'Pragma: hack',
                'Content-Type: '.$mmtype,
                'Content-Length: ' .filesize($filepath),
                'Content-Disposition: attachment; filename="'.basename($filepath).'"',
                'Content-Transfer-Encoding: binary'."\n"
            ];

            // set headers
            $this->set($h);

            // release now + ob_start() fix archive corruption
            ob_start();
            $this->release();
            ob_end_clean();

            // output file content
            readfile($filepath);

            die();          
        }
        return $this;
    }

    /**
     * Compress the file before setting header to download it.
     * Use gzip compression. @see gzencode
     * 
     * @param  string  $filepath      
     * @param  string  $archive_name  
     * @param  integer $compress_level
     * 
     * @return object $this
     */
    public function downloadCompressed($filepath, $archive_name, $compress_level = 1)
    {
        if(file_exists($filepath)) {

            $tmppath = sys_get_temp_dir();
            $tmpfile = $tmppath.'/'.$archive_name;

            // grap & compress file content
            $content = gzencode(file_get_contents($filepath), $compress_level);
            file_put_contents($tmpfile, $content);

            // download header
            $h = [
                'Cache-Control: public, must-revalidate',
                'Pragma: hack',
                'Content-Type: application/octet-stream',
                //'Content-Encoding: gzip',
                'Content-Length: ' .filesize($tmpfile),
                'Content-Disposition: attachment; filename="'.$archive_name.'"',
                'Content-Transfer-Encoding: binary'."\n"
            ];

            // set headers
            $this->set($h);

            // release now + ob_start() fix archive corruption
            ob_start();
            $this->release();
            ob_end_clean();

            // output file content
            readfile($tmpfile);

            die();
        }
        return $this;
    }
}