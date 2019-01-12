<?php

declare(strict_types=1);

namespace Peak\View;

class Presentation
{
    /**
     * @var array
     */
    private $sources;

    /**
     * Presentation constructor.
     * @param array $sources
     * @param string|null $basePath
     */
    public function __construct(array $sources, string $basePath = null)
    {
        $this->sources = $sources;
        if (isset($basePath)) {
            $this->sources = $this->addBasePath($sources, $basePath);
        }
    }

    /**
     * @return array
     */
    public function getSources(): array
    {
        return $this->sources;
    }

    /**
     * @param array $sources
     * @param string $basePath
     * @return array
     */
    private function addBasePath(array $sources, string $basePath): array
    {
        foreach ($sources as $i => $source) {
            if (is_array($source)) {
                unset($sources[$i]);
                $i = $basePath.$i;
                $sources[$i] = $this->addBasePath($source, $basePath);
            } else {
                $sources[$i] = $basePath.$source;
            }
        }
        return $sources;
    }
}
