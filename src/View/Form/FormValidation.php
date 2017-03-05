<?php

namespace Peak\View\Form;

use Peak\View\Form\FormBuilder;
use Peak\View\Form\FormDataSet;

/**
 * Form validation wrapper around
 * FormBuilder and FormDataSet
 */
class FormValidation
{
    /**
     * Form builder instance
     * @var Peak\View\Form\FormBuilder
     */
    protected $form_builder;

    /**
     * Validation dataset
     * @var Peak\View\Form\FormDataSet
     */
    protected $dataset;

    /**
     * Constructor
     *
     * @param FormBuilder $form_builder
     */
    public function __construct(FormBuilder $form_builder)
    {
        $this->form_builder = $form_builder;
        $this->createDataSet();
    }

    /**
     * Create DataSet
     */
    public function createDataSet()
    {
        $this->dataset = new FormDataSet();

        foreach ($this->form_builder as $name => $data) {
            if (isset($data['validation']) && is_array($data['validation'])) {
                foreach ($data['validation'] as $rule) {
                    $this->dataset->add($name, $rule);
                }
                //print_r($this->dataset);
            }
        }
    }

    /**
     * Validate 
     *
     * @param  array $data
     * @return bool
     */
    public function validate($data)
    {
        return $this->dataset->validate($data);
    }
}
