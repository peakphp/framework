<?php

declare(strict_types=1);

namespace Peak\Config\Stream;

use Peak\Blueprint\Config\Stream;
use Peak\Config\FilesHandlers;

/**
 * Class FileStream
 * @package Peak\Config\Stream
 */
class FileStream implements Stream
{
    /**
     * @var FilesHandlers
     */
    protected $handlers;

    /**
     * @var string
     */
    protected $file;

    /**
     * FileStream constructor.
     *
     * @param string $file
     * @param FilesHandlers $handlers
     */
    public function __construct(string $file, FilesHandlers $handlers)
    {
        $this->file = $file;
        $this->handlers = $handlers;
    }

    /**
     * @return array
     * @throws \Peak\Config\Exception\NoFileHandlersException
     */
    public function get(): array
    {
        $ext = strtolower(pathinfo($this->file, PATHINFO_EXTENSION));

        $loader = $this->handlers->getLoader($ext);
        $processor = $this->handlers->getProcessor($ext);

        return $processor->process($loader->load($this->file));
    }
}
