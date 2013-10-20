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
 * The file type filter.
 *
 * @author      Jan Papousek
 * @version		$Id: FileTypeFilter.php 61 2009-08-04 21:24:36Z jan.papousek $
 * @see         FileType
 */
class FileTypeFilter extends /*Object\*/Object
{

	/**
	 * The filter types.
	 *
	 * @var array|int
	 */
	private $types;

	/**
	 * It creates nre file type filter.
	 *
	 * @param array|int $types The supported types.
	 */
	public function  __construct(array $types) {
		$this->types = $types;
	}

	/**
	 * Factory method which creates new filter
	 * containing types of images used on web.
	 *
	 * @return array|int
	 */
	public static function crateWebImagesFilter() {
		return new FileTypeFilter(array(
			FileType::BMP,
			FileType::GIF,
			FileType::JPEG,
			FileType::PNG
		));
	}

	/**
	 * It checks if the file has supported file type.
	 *
	 * @param File $file
	 * @return boolean
	 * @throws NullPointerException if the $file is empty.
	 */
	public function accepts(File $file) {
		$type = $file->getType();
		foreach ($this->types AS $typeCode) {
			if ($typeCode == $type->getTypeCode()) {
				return TRUE;
			}
		}
		return FALSE;
	}

}
