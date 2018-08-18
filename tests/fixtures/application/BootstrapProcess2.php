<?php

use \Peak\Blueprint\Common\Bootable;
use \Peak\Collection\Collection;

class BootstrapProcess2 implements Bootable
{
    /**
     * @var Collection
     */
    private $collection;

    /**
     * BootstrapProcess constructor.
     */
    public function __construct(Collection $collection)
    {
        $this->collection = $collection;
    }

    public function boot()
    {
    }
}