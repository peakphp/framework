<?php

namespace Peak\Rbac;

abstract class AbstractHolder
{
    /**
     * Identifier of the holder
     * @var string
     */
    protected $id;

    /**
     * Constructor
     *
     * @param string $id User identifier name
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Get role identifier name
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
}
