<?php

declare(strict_types=1);

namespace Peak\Bedrock\View\Form;

/**
 * Interface ElementInterface
 * @package Peak\Bedrock\View\Form
 */
interface ElementInterface
{
    /**
     * Executed stuff just right after __construct()
     */
    public function init();

    /**
     * Get the html content
     * @return string
     */
    public function generate();
}
