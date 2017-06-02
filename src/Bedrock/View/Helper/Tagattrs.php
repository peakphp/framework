<?php

namespace Peak\Bedrock\View\Helper;

use Peak\Bedrock\View\Helper;

/**
 * Manage html tag attributes
 */
class Tagattrs extends Helper
{
    /**
     * Attributes
     * @var array
     */
    protected $attributes = [];
    
    /**
     * Add an array of attributes to the current class $attributes array
     *
     * @param  array $attrs
     * @return object
     */
    public function addAttrs(array $attrs)
    {
        foreach ($attrs as $k => $v) {
            $this->attributes[$k] = $v;
        }
        return $this;
    }
    
    /**
     * Overwrite class attributes with an array
     *
     * @param  array $attrs
     * @return object
     */
    public function setAttrs(array $attrs)
    {
        $this->attributes = $attrs;
        return $this;
    }
    
    /**
     * Set/Add value to 'class' attribute
     *
     * @param   string $name
     * @return  object
     */
    public function addClass($name)
    {
        if (isset($this->attributes['class'])) {
            $this->attributes['class'] .= ' '.$name;
        } else {
            $this->setClass($name);
        }
        return $this;
    }
    
    /**
     * Set/Overwrite value to 'class' attribute
     *
     * @param  string $name
     * @return object
     */
    public function setClass($name)
    {
        $this->attributes['class'] = $name;
        return $this;
    }
    
    /**
     * Set value to 'id' attribute
     *
     * @param  string $id
     * @return object
     */
    public function setId($id)
    {
        $this->attributes['id'] = $id;
        return $this;
    }
    
    /**
     * Set value to 'name' attribute
     *
     * @param  string $name
     * @return object
     */
    public function setName($name)
    {
        $this->attributes['name'] = $name;
        return $this;
    }
    
    /**
     * Set/Add array of key/val to 'style' attribute
     *
     * @param  array $style
     * @return object
     */
    public function addStyle(array $style)
    {
        if (isset($this->attributes['style'])) {
            foreach ($style as $k => $v) {
                $this->attributes['style'][$k] = $v;
            }
        } else {
            $this->setStyle($style);
        }
        return $this;
    }
    
    /**
     * Set/Overwrite array of key/val to 'style' attribute
     *
     * @param  array $style
     * @return object
     */
    public function setStyle(array $style)
    {
        $this->attributes['style'] = $style;
        return $this;
    }
    
    /**
     * Transform all previously added attributes into string
     *
     * @return string
     */
    public function __toString()
    {
        $attrs_string = '';
        foreach ($this->attributes as $k => $v) {
            if (!is_array($v)) {
                $attrs_string .= ' '.$k.'="'.$v.'"';
            } elseif ($k === 'style') {
                $attrs_string .= ' style="';
                foreach ($v as $css_key => $css_val) {
                    $attrs_string .=  $css_key.':'.$css_val.';';
                }
                $attrs_string .= '"';
            } else {
                $attrs_string .= ' '.$k.'="';
                foreach ($v as $av) {
                    $attrs_string .=  $av.' ';
                }
                $attrs_string .= '"';
            }
        }
        return $attrs_string;
    }
    
    /**
     * Reset all attributes
     *
     * @return object
     */
    public function reset()
    {
        $this->attributes = [];
        return $this;
    }
}
