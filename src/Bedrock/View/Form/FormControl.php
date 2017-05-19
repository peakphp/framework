<?php

namespace Peak\Bedrock\View\Form;

use Peak\Bedrock\View\Form\Element;

/**
 * Form control base
 */
abstract class FormControl extends Element
{
    /**
     * Overload those props as you need
     */
    protected $default_options = [
        'label'       => '',
        'description' => '',
        'attrs'       => [],
    ];

    /**
     * Contructor overload
     * Add error support for control
     *
     * @see  Peak\Bedrock\View\Form\Element::__construct()
     */
    public function __construct($name, $data, $options = [], $error = null)
    {
        $this->error = $error;
        parent::__construct($name, $data, $options);
    }

    /**
     * Default this method
     *
     * @return string
     */
    public function error()
    {
        if (empty($this->error)) {
            return;
        }

        $element = new \Peak\Bedrock\View\Form\Element\Error(
            $this->name, 
            $this->error, 
            [
                'attrs' => [
                    'id'   => null,
                    'name' => null
                ]
            ]
        );

        return $element->generate();
    }

    /**
     * Echo label() and generate()
     */
    public function renderWithLabel()
    {
        echo $this->label();
        echo $this->generate();
        echo $this->error();
    }

    /**
     * Render a generic label
     *
     * @return string
     */
    public function label()
    {
        if (empty($this->options['label'])) {
            return;
        }

        $element = new \Peak\Bedrock\View\Form\Element\Label(
            $this->name, 
            $this->options['label'],
            [
                'attrs' => [
                    'id'   => null,
                    'name' => null
                ]
            ]
        );

        return $element->generate();
    }
}
