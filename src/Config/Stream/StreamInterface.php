<?php

declare(strict_types=1);

namespace Peak\Config\Stream;

interface StreamInterface
{
    /**
     * @return mixed
     */
    public function get();
}
