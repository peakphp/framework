<?php
namespace Peak\Common;

use \Exception;

class ExceptionLogger
{
    const MODE_NORMAL = 1;
    const MODE_VERBOSE = 2;
    const MODE_CUSTOM = 3;

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
     * @var mixed
     */
    protected $custom_closure;

    /**
     * Constructor
     * @param Exception $exception
     * @param string $filepath
     * @param int $mode
     * @param null|callable $custom_closure
     */
    public function __construct(Exception $exception, $filepath, $mode = self::MODE_NORMAL, $custom_closure = null)
    {
        $this->exception = $exception;
        $this->filepath  = $filepath;
        $this->mode = $mode;
        $this->custom_closure = $custom_closure;
        $this->log();
    }

    /**
     * Log exception
     */
    protected function log()
    {
        if ($this->mode == self::MODE_VERBOSE) {
            $content = $this->verboseContent();
        } elseif ($this->mode == self::MODE_CUSTOM) {
            if (!is_callable($this->custom_closure)) {
                throw new Exception(__CLASS__.': you need to specify a closure when using custom mode');
            }
            $fn = $this->custom_closure;
            $content = $fn($this->exception);
        } else {
            $content = $this->normalContent();
        }

        $this->write($content);
    }

    /**
     * Write content
     * @param $content
     * @throws Exception
     */
    protected function write($content)
    {
        if ( (!file_exists($this->filepath) && !is_writable(dirname($this->filepath))) ||
            (file_exists($this->filepath) && !is_writable($this->filepath))) {
            throw new Exception(__CLASS__.': cannot write to ['.$this->filepath.']');
        }

        file_put_contents($this->filepath, $content, FILE_APPEND);
    }

    /**
     * MODE_VERBOSE
     * @return string
     */
    protected function verboseContent()
    {
        $content = exceptionTrace($this->exception);
        $content = strip_tags($content)."\n\n";
        return $content;
    }

    /**
     * MODE_NORMAL
     * @return string
     */
    protected function normalContent()
    {
        $msg = trim($this->exception->getMessage());
        $content = '['.date('Y-m-d H:i:s')."] ".get_class($this->exception)."\n";
        $content .= $msg."\n\n";
        return $content;
    }
}
