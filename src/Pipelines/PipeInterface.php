<?php

namespace Peak\Pipelines;

interface PipeInterface
{
    public function __invoke($payload);
}
