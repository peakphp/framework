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
     * @var mixed
     */
    protected $context;

    /**
     * Constructor
     *
     * @param array   $options rules options array
     * @param integer $flags   rules flags
     * @param mixed   $context rules context data
     */
    public function __construct(array $options = [], int $flags = null, $context = null)
    {
        $this->options = $this->defaultOptions;
        $this->flags = $flags;
        $this->context = $context;

        if (is_array($options)) {
            $this->options = array_merge($this->defaultOptions, $options);
            // remove null options
            foreach ($this->options as $key => $value) {
                if ($value === null) {
                    unset($this->options[$key]);
                }
            }
        }

        $this->init();
    }

    /**
     * Init after merging options
     */
    public function init()
    {
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @return int
     */
    public function getFlags(): ?int
    {
        return $this->flags;
    }

    /**
     * @return mixed
     */
    public function getContext()
    {
        return $this->context;
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
