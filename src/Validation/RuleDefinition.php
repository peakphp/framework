<?php

namespace Peak\Validation;

class RuleDefinition
{
    /**
     * Default options
     * @var array
     */
    protected $default_def = [
        'rule'    => null,
        'options' => null,
        'flags'   => null,
        'error'   => null,
    ];

    /**
     * Final rule definitions
     * @var array
     */
    protected $def = [];

    /**
     * Constructor
     *
     * @param array $def
     */
    public function __construct($def = null)
    {
        $this->def = $this->default_def;

        if (is_array($def)) {
            $this->def = array_merge($this->default_def, $def);
        }
    }

    /**
     * Get an defition by key
     *
     * @param  string $key
     * @return mixed
     */
    public function &__get($key)
    {
        return $this->def[$key];
    }
}
