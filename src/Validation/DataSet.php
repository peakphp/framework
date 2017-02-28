<?php

namespace Peak\Validation;

use Peak\Validation\RuleDefinition;

/**
 * Validation rules for a data set
 */
abstract class DataSet
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
    final public function __construct()
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
     * @param string $name        data key name
     * @param array  $definitions rules definitions
     */
    public function add($name, ...$definitions)
    {
        if(!array_key_exists($name, $this->rules)) {
            $this->rules[$name] = [];
        }

        foreach($definitions as $def) {
            array_push($this->rules[$name], $def);
        }

        // print_r($this->rules);

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
     * @param  array $data 
     * @return bool
     */
    public function validate($data)
    {
        $this->errors = [];

        if(empty($this->rules)) {
            return false;
        }

        foreach($this->rules as $key => $val) {

            if(!array_key_exists($key, $data)) {
                if(in_array('required', $val)) {
                    $this->errors[$key] = 'Required';
                }
                continue;
            }

            // check one field
            foreach($val as $def) {

                if(!is_array($def)) continue;

                $rule_def = new RuleDefinition($def);

                $rule = Rule::create($rule_def->rule)
                    ->setOptions($rule_def->options)
                    ->setFlags($rule_def->flags)
                    ->setError($rule_def->error);

                if(!$rule->validate($data[$key])) {
                    $this->errors[$key] = $rule->getError();
                }
            }
        }

        return (empty($this->errors));   
    }    
}
