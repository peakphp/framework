<?php

namespace Peak\Bedrock\View;

interface RenderableInterface
{
    /**
     * Transform something into text, html, etc
     *
     * @return string
     */
    public function render();
}