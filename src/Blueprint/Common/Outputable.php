<?php

namespace Peak\Blueprint\Common;

interface Outputable
{
    /**
     * Output data
     * @param mixed $data Data content to output
     */
    public function output($data);
}
