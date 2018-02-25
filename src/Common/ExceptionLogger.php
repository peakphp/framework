<?php

declare(strict_types=1);

namespace Peak\Common;

use \Exception;

class ExceptionLogger
{

    const MODE_NORMAL = 1;
    const MODE_VERBOSE = 2;
    const MODE_CLOSURE = 3;
    const MODE_CUSTOM = 4;

    /**
     * @var array
     */
    protected $modes = [
        self::MODE_NORMAL => 'normalContent',
        self::MODE_VERBOSE => 'verboseContent',
        self::MODE_CLOSURE => 'closureContent',
        self::MODE_CUSTOM => 'customContent',
    ];

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
     * @var array
     */
    protected $default_content = [];

    /**
     * Constructor
     *
     * @param Exception $exception
     * @param string $filepath
     * @param int $mode
     * @param mixed $custom
     * @throws Exception
     */
    public function __construct(Exception $exception, string $filepath, int $mode = self::MODE_NORMAL, $custom = null)
    {
        $this->exception = $exception;
        $this->filepath  = $filepath;
        $this->mode = $mode;
        $this->custom = $custom;

        $this->initDefaultContent();
        $this->log();
    }

    /**
     * Default content
     */
    protected function initDefaultContent(): void
    {
        $this->default_content = [
            'datetime' => date('Y-m-d H:i:s'),
            'date' => date('Y-m-d'),
            'time' => date('H:i:s'),
            'exception' => trim($this->exception->getMessage()),
            'exception_class' => get_class($this->exception),
            'exception_name' => shortClassName($this->exception),
        ];
    }

    /**
     * Log exception
     *
     * @throws Exception
     */
    protected function log(): void
    {
        if (!isset($this->modes[$this->mode])) {
            throw new Exception(__CLASS__.' mode is invalid');
        }

        $method = $this->modes[$this->mode];
        $content = $this->$method();

        $this->write($content);
    }

    /**
     * Write content
     *
     * @param $content
     * @throws Exception
     */
    protected function write(string $content): void
    {
        if ( (!file_exists($this->filepath) && !is_writable(dirname($this->filepath))) ||
            (file_exists($this->filepath) && !is_writable($this->filepath))) {
            throw new Exception(__CLASS__.': cannot write to ['.$this->filepath.']');
        }

        file_put_contents($this->filepath, $content, FILE_APPEND);
    }

    /**
     * MODE_VERBOSE
     *
     * @return string
     */
    protected function verboseContent(): string
    {
        $content = exceptionTrace($this->exception);
        $content = strip_tags($content)."\n\n";
        return $content;
    }

    /**
     * MODE_CLOSURE
     *
     * @return string
     * @throws Exception
     */
    protected function closureContent(): string
    {
        if (!is_callable($this->custom_closure)) {
            throw new Exception(__CLASS__.': you need to specify a closure when using custom mode');
        }
        $fn = $this->custom_closure;
        return $fn($this->exception);
    }

    /**
     * MODE_CUSTOM
     *
     * @return string
     * @throws Exception
     */
    protected function customContent(): string
    {
        $content = array_merge($this->default_content, $this->custom['content']);
        return interpolate($this->custom['message'], $content);
    }

    /**
     * MODE_NORMAL
     *
     * @return string
     */
    protected function normalContent(): string
    {
        $message = "[{datetime}] {exception_class}\n{exception}\n\n";
        return interpolate($message, $this->default_content);
    }
}
