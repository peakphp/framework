<?php
/**
 * Abstract class for code generation
 * 
 * @author  Francois Lajoie
 * @version $Id$
 */
abstract class Peak_Codegen
{
	/**
	 * Indentation space
	 */
	const INDENTATION_SPACE = 4;

	/**
	 * Line break symbol
	 */
	const LINE_BREAK = "\n";

	/**
	 * PHP open tag
	 */
	const PHP_OPEN_TAG = '<?php';

	/**
	 * PHP close tag
	 */
	const PHP_CLOSE_TAG = '?>';

	/**
	 * Get indentation string
	 *
	 * @param  integer $multiplicator Represent number of indentation(s). By default 1
	 * @return string
	 */
    public function getIndent($multiplicator = 1)
	{
		$pad_length = self::INDENTATION_SPACE * $multiplicator;
		return str_pad('',$pad_length);
	}

	/**
	 * Get preview of generated code
	 *
	 * @return string
	 */
	public function preview()
	{
		$this->preGenerate();
		return $this->generate();
	}

	/**
	 * Save generated code
	 *
	 * @param  string $filepath filepath       where content will be saved, if file doesn't exists it will create it.
	 * @param  bool   $add_php_open_tag        add php open tag in file content
	 * @param  misc   $file_put_contents_flags add file_put_contents_flags() flags
	 * @return bool
	 */
	public function save($filepath, $add_php_open_tag = false, $file_put_contents_flags = 0)
	{
		$data = $this->preview();
		
		if($add_php_open_tag) {
		    $data = Peak_Codegen::PHP_OPEN_TAG . Peak_Codegen::LINE_BREAK . $data;
		}
		
		$result = file_put_contents($filepath, $data, $file_put_contents_flags);
		return $result;
	}

	/**
	 * Optionnal, Can be overloaded by child. Will be call each time before generate()
	 */
	public function preGenerate() { }
	
	/**
	 * Need to be overloaded by child
	 */
	abstract public function generate();

}