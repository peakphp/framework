<?php


class InvokableMiddleware
{
    public function __invoke()
    {
        return new ResponseA();
    }
}