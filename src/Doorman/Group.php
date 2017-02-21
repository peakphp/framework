<?php

namespace Peak\Doorman;

use Peak\Doorman\PolicySubjectInterface;

/**
 * Group entity
 */
class Group implements PolicySubjectInterface
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
