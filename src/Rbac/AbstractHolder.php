<?php

declare(strict_types=1);

namespace Peak\Rbac;

/**
 * Class AbstractHolder
 * @package Peak\Rbac
 */
abstract class AbstractHolder
{
    /**
     * Identifier of the holder
     * @var string
     */
    protected $id;

    /**
     * Holder description
     * @var string
     */
    protected $description;

    /**
     * AbstractHolder constructor.
     *
     * @param string $id
     * @param string $description
     */
    public function __construct($id, string $description = '')
    {
        $this->id = $id;
        $this->description = $description;
    }

    /**
     * Get holder identifier name
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get holder description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}
