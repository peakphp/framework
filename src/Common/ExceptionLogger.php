<?php
namespace Peak\Common;

use \Exception;

class ExceptionLogger
{
    const MODE_NORMAL = 1;
    const MODE_VERBOSE = 2;
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
     * Log mode
     * @var int
     */
    protected $mode;

    /**
     * Constructor
     * @param Exception $exception
     * @param $filepath
     * @param int $mode
     */
    public function __construct(Exception $exception, $filepath, $mode = self::MODE_NORMAL)
    {
        $this->exception = $exception;
        $this->filepath  = $filepath;
        $this->mode = $mode;
        $this->log();
    }

    /**
     * Log the error to a file
     */
    protected function log()
    {
        if ($this->mode == self::MODE_VERBOSE) {
            $content = exceptionTrace($this->exception);
            $content = strip_tags($content)."\n\n";
        } else {
            $msg = trim($this->exception->getMessage());
            $content = '['.date('Y-m-d H:i:s')."] ".get_class($this->exception)."\n";
            $content .= $msg."\n\n";
        }

        if ( (!file_exists($this->filepath) && !is_writable(dirname($this->filepath))) ||
            (file_exists($this->filepath) && !is_writable($this->filepath))) {
            throw new Exception(__CLASS__.': cannot write to ['.$this->filepath.']');
        }

        file_put_contents($this->filepath, $content, FILE_APPEND);
    }
}
