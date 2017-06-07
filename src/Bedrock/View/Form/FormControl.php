<?php

namespace Peak\Bedrock\View\Form;

use Peak\Bedrock\View\Form\Element;
use Peak\Bedrock\View\Form\Element\Error;
use Peak\Bedrock\View\Form\Element\Label;

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
     * Default this method
     *
     * @return string
     */
    public function error()
    {
        if (empty($this->error)) {
            return;
        }

        $element = new Error(
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
     * Echo error()
     */
    public function renderError()
    {
        echo $this->error();
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

        $element = new Label(
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
