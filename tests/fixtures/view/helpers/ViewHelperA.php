<?php

declare(strict_types=1);

class ViewHelperA
{
    /**
     * @param string $name
     * @return string
     */
    public function __invoke(string $name)
    {
        return 'Hello ' . $name . '!';
    }
}