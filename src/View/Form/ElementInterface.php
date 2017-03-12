<?php

namespace Peak\View\Form;

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
