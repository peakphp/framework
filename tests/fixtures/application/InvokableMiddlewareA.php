<?php


class InvokableMiddlewareA
{
    public function __invoke()
    {
        return new ResponseA();
    }
}