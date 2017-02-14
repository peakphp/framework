<?php

namespace Peak\Spl;

use DirectoryIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * Retreive directory sizes and number of files
 */
class Dirinfo
{
	/**
	 * Size
	 * @var integer
	 */
	protected $_size = 0;

	/**
	 * Number of files
	 * @var integer
	 */
	protected $_nbfiles = 0;

	/**
	 * Gather information about directory
	 * 
	 * @param string $path
	 */
	public function __construct($path, $recursive = true) 
	{
		if($recursive) {
			$it = new RecursiveDirectoryIterator($path);

			foreach (new RecursiveIteratorIterator($it) as $f => $c) {
				if($c->isDir() || $c->isDot()) continue;
				$size = $c->getSize();
				$this->_size += $size;
				++$this->_nbfiles;
			}
		}
		else {
			foreach(new DirectoryIterator($path) as $f) {
			    if($f->isDot()) continue;
			    $size = $f->getSize();
				$this->_size += $size;
				++$this->_nbfiles;
			}
		}
	}

	/**
	 * Return directory size
	 *
	 * @param  bool $format
	 * @return string|integer
	 */
	public function getSize($format = false)
	{
		if(!$format) return $this->_size;
		else {
            $unit = array('b','kb','mb','gb','tb','pb');
            return @round($this->_size/pow(1024,($i=floor(log($this->_size,1024)))),2).' '.$unit[$i];
		}
	}

	/**
	 * Return number of files of directory 
	 *
	 * @return integer
	 */
	public function getNbfiles()
	{
		return $this->_nbfiles;
	}
}
