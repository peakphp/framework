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
    protected $_attrs = array();
    
    /**
     * Add an array of atrributes to the current class $_attrs array
     *
     * @param  array $attrs
     * @return object
     */
    public function addAttrs(array $attrs)
    {
        foreach ($attrs as $k => $v) $this->_attrs[$k] = $v;
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
        $this->_attrs = $attrs;
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
        if (isset($this->_attrs['class'])) {
            $this->_attrs['class'] .= ' '.$name;
        }
        else $this->setClass($name);
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
        $this->_attrs['class'] = $name;
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
        $this->_attrs['id'] = $id;
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
        $this->_attrs['name'] = $name;
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
        if (isset($this->_attrs['style'])) {
            foreach ($style as $k => $v) {
                $this->_attrs['style'][$k] = $v;
            }
        }
        else $this->setStyle($style);
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
        $this->_attrs['style'] = $style;
        return $this;
    }
    
    /**
     * Tranform all previously added attributes into string
     *
     * @return string
     */
    public function __toString()
    {
        $attrs_string = '';
        foreach ($this->_attrs as $k => $v) {
            if (!is_array($v)) $attrs_string .= ' '.$k.'="'.$v.'"';
            elseif ($k === 'style') {
                $attrs_string .= ' style="';
                foreach ($v as $css_key => $css_val) $attrs_string .=  $css_key.':'.$css_val.';';
                $attrs_string .= '"';
            }
            else {
                $attrs_string .= ' '.$k.'="';
                foreach ($v as $av) $attrs_string .=  $av.' ';
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
        $this->_attrs = array();
        return $this;
    }
}
