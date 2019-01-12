<?php

declare(strict_types=1);

namespace Peak\Validation;

use Peak\Validation\Definition\RuleArrayDefinition;

/**
 * Validation rules for a data set
 */
abstract class AbstractDataSet
{
    /**
     * Data Rules definitions
     * @var array
     */
    protected $rules = [];

    /**
     * Data validation errors
     * @var array
     */
    protected $errors = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setUp();
    }

    /**
     * SetUp data rules
     */
    abstract public function setUp();

    /**
     * Add to data one or many rules definitions
     *
     * @param  string $name        data key name
     * @param  array  $definitions rules definitions
     * @return $this
     */
    public function add($name, ...$definitions)
    {
        if (!array_key_exists($name, $this->rules)) {
            $this->rules[$name] = [];
        }

        foreach ($definitions as $def) {
            array_push($this->rules[$name], $def);
        }

        return $this;
    }

    /**
     * Get validation errors
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Validate data with current rules
     *
     * @param mixed $data
     * @return bool
     * @throws \Exception
     */
    public function validate($data)
    {
        $this->errors = [];

        if (empty($this->rules)) {
            return true;
        }

        foreach ($this->rules as $key => $val) {
            // look for special condition
            if (!array_key_exists($key, $data)) {
                if (in_array('required', $val)) {
                    $this->errors[$key] = 'Required';
                }
                continue;
            } elseif (empty($data[$key])) {
                if (in_array('if_not_empty', $val)) {
                    continue;
                }
            }

            // process rule(s) validation on data key value
            foreach ($val as $definition) {
                if (!is_array($definition)) {
                    continue;
                }

                if (in_array('empty', $val)) {
                    $this->errors[$key] = 'Required';
                }

                $rule = new Rule(new RuleArrayDefinition($definition));


                if (!$rule->validate($data[$key])) {
                    $this->errors[$key] = $rule->getErrorMessage();
                    break;
                }
            }
        }

        return (empty($this->errors));
    }
}
