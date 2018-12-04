<?php

namespace Peak\Bedrock\Http\Middleware;


use Psr\Http\Message\ResponseInterface;

class ResponseStack implements \Peak\Blueprint\Http\ResponseMiddleware
{
    public function __construct()
    {
    }
}