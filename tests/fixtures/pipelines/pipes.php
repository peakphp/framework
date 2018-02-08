<?php

use Peak\Pipelines\PipeInterface;

/**
 * Pipe with invoke
 */
class Pipe1 implements PipeInterface
{
    public function __invoke($payload)
    {
        return ++$payload;
    }
}

/**
 * Pipe as class
 */
class Pipe2 implements PipeInterface
{
    public function __invoke($payload)
    {
        return ++$payload;
    }
}

/**
 * Pipe as class with
 */
class Pipe3 implements PipeInterface
{
    public function __construct(\Peak\Common\Collection $coll)
    {
        $this->coll = $coll;
    }

    public function __invoke($payload)
    {
        $this->coll->payload = ++$payload;
        return $this->coll;
    }
}


/**
 * Pipe with invoke
 */
class Pipe10 implements PipeInterface
{
    public function __invoke($payload)
    {
        $payload .= 'A';
        return $payload;
    }
}

/**
 * Pipe with invoke
 */
class Pipe11 implements PipeInterface
{
    public function __invoke($payload)
    {
        $payload .= 'B';
        return $payload;
    }
}

/**
 * Pipe with invoke
 */
class Pipe12 implements PipeInterface
{
    public function __invoke($payload)
    {
        $payload .= 'C';
        return $payload;
    }
}

/**
 * Pipe with invoke
 */
class Pipe13 implements PipeInterface
{
    public function __invoke($payload)
    {
        $payload .= 'D';
        return $payload;
    }
}

/**
 * Pipe missing PipeInterface
 */
class Pipe14
{
    public function __invoke($payload)
    {
        $payload = 1;
        return $payload;
    }
}

function pipeFunction($payload) {
    return ++$payload;
}