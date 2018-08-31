<?php

declare(strict_types=1);

namespace Peak\Validation;

use Peak\Blueprint\Common\Validator;

/**
 * Class AbstractRule
 * @package Peak\Validation
 */
abstract class AbstractRule implements Validator
{
    /**
     * Default options
     * @var array
     */
    protected $defaultOptions = [];

    /**
     * Options
     * @var array
     */
    protected $options;

    /**
     * Flags for php validate filters
     * @var integer
     */
    protected $flags;

    /**
     * Context data
     * @var array
     */
    protected $context;

    /**
     * Constructor
     *
     * @param array   $options rules options array
     * @param integer $flags   rules flags
     * @param array   $context rules context data
     */
    public function __construct($options = null, $flags = null, $context = null)
    {
        $this->options = $this->defaultOptions;

        if (is_array($options)) {
            $this->options = array_merge($this->defaultOptions, $options);
            // remove null options
            foreach ($this->options as $key => $value) {
                if ($value === null) {
                    unset($this->options[$key]);
                }
            }
        }

        $this->flags = $flags;
        $this->context = $context;

        $this->init();
    }

    /**
     * Init after merging options
     */
    public function init()
    {
    }

    /**
     * Get options array for filter_var()
     *
     * @return array
     */
    protected function getFilterVarOptions()
    {
        return [
            'options' => $this->options,
            'flags'   => $this->flags,
        ];
    }
}
