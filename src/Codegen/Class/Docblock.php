<?php

/**
 * Class docblock object
 *
 * @author  Francois Lajoie
 * @version $Id$
 */
class Peak_Codegen_Class_DocBlock
{
	/**
	 * Docblock title
	 * @var string
	 */
	private $_title;

	/**
	 * Docblock tags
	 * @var array
	 */
	private $_tags;

	/**
	 * Set docblock title
	 *
	 * @param  string $title
	 * @return object
	 */
	public function setTitle($title)
	{
		$this->_title = $title;
		return $this;
	}
	
	/**
	 * Add docblock tag
	 *
	 * @param  string $tag
	 * @param  string $text
	 * @return object
	 */
	public function addTag($tag, $text = null)
	{
		$this->_tags[] = array('tag' => $tag, 'text' => $text);
		return $this;
	}
	
	/**
	 * Generate docblock content
	 *
	 * @param  integer $indent
	 * @return string
	 */
	public function generate($indent = '')
	{
		$data = $indent.'/**' . Peak_Codegen::LINE_BREAK;
		$data .= $indent.' * '.$this->_title . Peak_Codegen::LINE_BREAK;
	
		if(!empty($this->_tags)) {
			$data .= $indent.' *'.Peak_Codegen::LINE_BREAK;
			foreach($this->_tags as $index => $tag) {
				$data .= $indent.' * @'.$tag['tag'].' '.$tag['text'].Peak_Codegen::LINE_BREAK;
			}
		}
		$data .= $indent.' */'.Peak_Codegen::LINE_BREAK;
		
		return $data;
	}

}