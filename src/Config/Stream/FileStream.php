<?php

declare(strict_types=1);

namespace Peak\Config\Stream;

use Peak\Blueprint\Config\Stream;
use Peak\Config\Exception\NoFileHandlersException;
use Peak\Config\FilesHandlers;
use function pathinfo;
use function strtolower;

class FileStream implements Stream
{
    protected FilesHandlers $handlers;

    protected string $file;

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
     * @throws NoFileHandlersException
     */
    public function get(): array
    {
        $ext = strtolower(pathinfo($this->file, PATHINFO_EXTENSION));

        $loader = $this->handlers->getLoader($ext);
        $processor = $this->handlers->getProcessor($ext);

        return $processor->process($loader->load($this->file));
    }
}
