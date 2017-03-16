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
        $content = $this->exception->getMessage();

        if ($this->exception instanceof \Peak\Exception) {
            $content = $this->exception->getDebugTrace();
        }

        $content = date('Y-m-d H:i:s')."\n".strip_tags($content)."\n\n";

        file_put_contents($this->filepath, $content, FILE_APPEND);
    }
}
