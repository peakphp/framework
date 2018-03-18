<?php

namespace Peak\Config;

interface ProcessorInterface
{
    public function process($data);
    public function getContent();
}
