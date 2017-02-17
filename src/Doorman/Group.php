<?php

namespace Peak\Doorman;

/**
 * Group entity
 */
class Group
{
    /**
     * Group name
     * @var string
     */
    protected $name;

    /**
     * Constructor
     * 
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Get group name
     * 
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
