<?php

namespace Peak\Bedrock\View;
use Peak\Common\TimeExpression;

/**
 * View helper - Http header
 */
class Header
{
    /**
     * Header fields
     * @var array
     */
    protected $header = [];

    /**
     * Additional content after
     * @var string
     */
    protected $content;

    /**
     * Did we release header fields
     * @var boolean
     */
    protected $released = false;

    /**
     * Hold stop release() method from outputting the stuff
     * @var boolean
     */
    protected $hold = false;

    /**
     * List of http status codes
     * @var array
     */
    protected $http_status_codes = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',                 // RFC 2518, obsoleted by RFC 4918

        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',               // RFC 4918
        208 => 'Already Reported',
        226 => 'IM Used',

        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',         // RFC 7238

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
        413 => 'Payload Too Large',
        414 => 'URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',              // RFC 2324
        421 => 'Misdirected Request',
        422 => 'Unprocessable Entity',       // RFC 4918
        423 => 'Locked',                     // RFC 4918
        424 => 'Failed Dependency',          // RFC 4918
        425 => 'Unordered Collection',       // RFC 4918
        426 => 'Upgrade Required',           // RFC 2817
        428 => 'Precondition Required',      // RFC 6585
        429 => 'Too Many Requests',          // RFC 6585
        431 => 'Request Header Fields Too Large',// RFC 6585
        451 => 'Unavailable For Legal Reasons',

        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates',    // RFC 2295
        507 => 'Insufficient Storage',       // RFC 4918
        508 => 'Loop Detected',
        509 => 'Bandwidth Limit Exceeded',
        510 => 'Not Extended',               // RFC 2774
        511 => 'Network Authentication Required' // RFC 6585
    ];

    /**
     * "Release" header (simply call php header()) and content if any
     *
     * @param bool $die
     */
    public function release($die = false)
    {
        if ($this->hold === true) {
            return;
        }

        if (!empty($this->header) && !headers_sent() && $this->released === false) {
            $this->released = true;
            foreach ($this->header as $field) {
                header($field);
            }
        }
        if (!empty($this->content)) {
            echo $this->content;
        }
        if ($die) {
            $this->stop();
        }
    }

    /**
     * Hold the header releasing
     */
    public function holdOn()
    {
        $this->hold = true;
        return $this;
    }

    /**
     * Hold the header releasing
     */
    public function holdOff()
    {
        $this->hold = false;
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
        if (!empty($field)) {
            if (!is_array($field)) {
                $this->header[] = $field;
            } else {
                foreach ($field as $f) {
                    $this->header[] = $f;
                }
            }
        }
        return $this;
    }

    /**
     * Check if has an header field(line)
     *
     * @param string $header_field
     * @return bool
     */
    public function has($header_field)
    {
        return in_array($header_field, $this->header);
    }

    /**
     * Set content after header release
     *
     * @param  string $data
     * @return object $this
     */
    public function setContent($data)
    {
        $this->content = $data;
        return $this;
    }

    /**
     * Flush all headers and content
     *
     * @return $this
     */
    public function reset()
    {
        $this->content  = '';
        $this->header[] = [];
        $this->released = false;
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
        if (array_key_exists($code, $this->http_status_codes)) {
            return $this->http_status_codes[$code];
        }
        return null;
    }

    /**
     * Set a valid http response code
     *
     * @param  integer     $code
     * @param  bool|string $die   if not false, but a string instead of true, it will die with $die string as message
     * @param  string      $http
     * @return $this
     */
    public function setCode($code = 200, $die = false, $http = 'HTTP/1.1')
    {
        if (array_key_exists($code, $this->http_status_codes)) {
            $this->set($http.' '.$code.' '.$this->http_status_codes[$code]);
        }

        if ($die !== false) {
            $this->release();
            if ($die === true) {
                $die = null;
            }
            $this->stop($die);
        }

        return $this;
    }

    /**
     * Set cache header
     *
     * @param mixed $max_age (int of string support by TimeExpression)
     * @param string $visibility
     * @return $this
     */
    public function setCache($max_age, $visibility = 'public')
    {
        if (!is_numeric($max_age)) {
            $max_age = (new TimeExpression($max_age))->toSeconds();
        }

        $directive = 'Cache-Control: max-age='.$max_age;
        if (!empty($visibility)) {
            $directive .= ','.$visibility;
        }

        $this->set($directive);

        return $this;
    }

    /**
     * Force a browser to use fresh content (no caching)
     *
     * @return $this
     */
    public function noCache()
    {
        $this->set([
            'Cache-Control: no-cache, must-revalidate',
            'Expires: Thu, 01 Jan 1970 00:00:00 GMT'
        ]);

        return $this;
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
     * @return $this
     */
    public function download($filepath, $mmtype = 'application/octet-stream')
    {
        if (file_exists($filepath)) {
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

            $this->stop();
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
     * @return $this
     */
    public function downloadCompressed($filepath, $archive_name, $compress_level = 1)
    {
        if (file_exists($filepath)) {
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
                'Content-Transfer-Encoding: binary'
            ];

            // set headers
            $this->set($h);

            // release now + ob_start() fix archive corruption
            ob_start();
            $this->release();
            ob_end_clean();

            // output file content
            readfile($tmpfile);

            $this->stop();
        }
        return $this;
    }

    /**
     * Stop php
     * @param null $message
     */
    protected function stop($message = null)
    {
        die($message);
    }
}
