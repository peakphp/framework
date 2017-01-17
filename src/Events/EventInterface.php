<?php
namespace Peak\Events;

interface EventInterface
{
    public function fire($argv);
}