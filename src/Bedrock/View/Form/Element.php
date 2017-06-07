<?php

namespace Peak\Bedrock\View\Form;

use Peak\Bedrock\View\Form\ElementInterface;

/**
 * Form element base
 */
abstract class Element implements ElementInterface
{
    /**
     * Overload those props as you need
     */
    protected $default_options = [
        'attrs' => [],
    ];

    /**
     * Attribute that may need translation
     * @var array
     */
    protected $attrs_to_translate = [
        'placeholder',
        'title'
    ];

    /**
     * Don't overload those props
     */
    protected $options     = [];
    protected $name        = '';
    protected $data        = null;
    protected $error       = null;
    protected $attrs_array = [];

    /**
     * Create an element
     *
     * @param string $name    internal name for this element
     * @param mixed  $data    current element data
     * @param array  $options elements options
     */
    public function __construct($name, $data, $options = [], $error = null)
    {
        $this->name  = $name;
        $this->data  = $data;
        $this->error = $error;

        if (!array_key_exists('attrs', $options)) {
            $options['attrs'] = [];
        }

        $this->options = $options;
        
        // merge attrs first
        $this->options['attrs'] = array_merge($this->default_options['attrs'], $this->options['attrs']);

        // merge the rest
        $this->options = array_merge($this->default_options, $this->options);

        $this->init();
    }

    /**
     * Get content
     *
     * @return string
     */
    public function get()
    {
        return $this->generate();
    }

    /**
     * Echo generate() content
     */
    public function render()
    {
        echo $this->generate();
    }

    /**
     * Generate html attributes from an associative array
     *
     * @param  bool   $data_as_attrs if true, data will be added to 'value' html attribute
     * @return string
     */
    protected function attributes($data_as_attrs = true)
    {
        $attrs_array = [
            'id'    => 'field-'.$this->name,
            'name'  => $this->name,
        ];

        if ($data_as_attrs) {
            $attrs_array['value'] = $this->data;
        }

        if (!empty($this->error)) {
            if (isset($this->options['attrs']['class'])) {
                $this->options['attrs']['class'] .= ' error';
            } else {
                $this->options['attrs']['class'] = 'error';
            }
        }

        if (is_null($this->options['attrs'])) {
            $this->options['attrs'] = [];
        }
        $attrs_array = array_merge($attrs_array, $this->options['attrs']);

        //create a copy before transforming it
        $this->attrs_array = $attrs_array;

        //print_r($attrs_array);

        //special cases
        if (array_key_exists('multiple', $attrs_array) && $attrs_array['multiple'] === true) {
            $attrs_array['name'] .= '[]';
        }

        $attrs_string = [];

        //transform to html attribute string ( key="value" )
        foreach ($attrs_array as $k => $v) {

            if ($v === null) {
                continue;
            }

            // if (in_array($k, $this->attrs_to_translate)) {
            //     $v = __($v);
            // }

            if (is_bool($v) && !is_integer($v)) {
                if ($v === true) {
                    $attrs_string[] = $k;
                }
                //echo $k.'BOOL ';
            } else {
                $attrs_string[] = $k.'="'.$v.'"';
            }
        }

        return implode(' ', $attrs_string);
    }

    /**
     * Check if string is json string
     *
     * @param  string $string
     * @return boolean
     */
    protected function isJson($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    /**
     * Bool to integer
     *
     * @param  boolean $bool
     * @return integer
     */
    protected function bool2Int($bool)
    {
        return ($bool === true) ? 1 : 0;
    }

    /**
     * Bool to string
     *
     * @param  boolean $bool
     * @return string
     */
    protected function bool2String($bool)
    {
        return ($bool === true) ? 'true' : 'false';
    }

    /**
     * Transform php array to javascript array
     *
     * @param  array $array
     * @return string
     */
    protected function array2JsArray($array)
    {
        $final = [];
        foreach ($array as $e) {
            $final[] = '"'.$e.'"';
        }
        return '['.implode(',', $final).']';
    }
}
