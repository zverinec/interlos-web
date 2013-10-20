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
 * This interface is designed to be implementde by file filters.
 *
 * @author      Jan Papousek
 * @version		$Id: IFileFilter.php 61 2009-08-04 21:24:36Z jan.papousek $
 * @see         FileTypeFilter
 * @see         FileNameFilter
 */
interface IFileFilter
{

	/**
	 * It checks if the file is accepted.
	 *
	 * @param File $file
	 * @return boolean
	 * @throws NullPointerException if the $file is empty.
	 */
	function accepts(File $file);

}
