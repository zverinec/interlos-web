<?php
/**
 * This source file is subject to the "New BSD License".
 *
 * For more information please see http://code.google.com/p/eskymofw/
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 *				Jan Drábek (repli2dev@gmail.com)
 * @license		http://www.opensource.org/licenses/bsd-license.php
 * @link		http://code.google.com/p/eskymofw/
 */

/**
 * The file name filter.
 *
 * @author      Jan Papousek
 * @version		$Id: FileNameFilter.php 61 2009-08-04 21:24:36Z jan.papousek $
 * @see         IFileFilter
 */
class FileNameFilter extends /*Nette\*/Object implements IFileFilter
{

	/**
	 * The rule describing the file name.
	 *
	 * @var string
	 */
	private $rule;

	/**
	 * It creates a new file name filter.
	 *
	 * @param string $rule The regular expression describing the file name.
	 * @throws NullPointerException if the $rule is empty.
	 */
	public function  __construct($rule) {
		if (empty($rule)) {
			throw new NullPointerException("rule");
		}
		$this->rule = $rule;
	}

	/**
	 * It returns a regular expression describing the file name.
	 *
	 * @return string
	 */
	public function getRule() {
		return $this->rule;
	}

	/**
	 * It checks if the file has supported file name.
	 *
	 * @param File $file
	 * @return boolean
	 * @throws NullPointerException if the $file is empty.
	 */
	public function accepts(File $file) {
		if (empty($file)) {
			throw new NullPointerException("file");
		}
		return (eregi($this->getRule(), $file->getName()));
	}

}