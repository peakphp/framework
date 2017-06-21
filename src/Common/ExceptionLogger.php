<?php
namespace Peak\Common;

use \Exception;

class ExceptionLogger
{
    /**
     * Exception object
     * @var Exception
     */
    protected $exception;

    /**
     * Log file path
     * @var string
     */
    protected $filepath;

    /**
     * Constructor
     *
     * @param object $exception
     * @param string $filepath
     */
    public function __construct(Exception $exception, $filepath)
    {
        $this->filepath  = $filepath;
        $this->exception = $exception;
        $this->log();
    }

    /**
     * Log the error to a file
     */
    protected function log()
    {
        $content = exceptionTrace($this->exception);
        $content = strip_tags($content)."\n\n";

        if ( (!file_exists($this->filepath) && !is_writable(dirname($this->filepath))) ||
            (file_exists($this->filepath) && !is_writable($this->filepath))) {
            throw new Exception(__CLASS__.': cannot write to ['.$this->filepath.']');
        }

        file_put_contents($this->filepath, $content, FILE_APPEND);
    }
}
