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
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$Id$
 */
class SimpleDeleter implements IDeleter
{
	/**
	 * Available instances
	 *
	 * @var array of SimpleDeleter
	 */
	private static $instances = array();

	/**
	 * Table which the deleter works with
	 *
	 * @var string
	 */
	private $table;

	/**
	 * It creates a new instance
	 *
	 * @param string $table
	 */
	private function  __construct($table) {
		$this->table = $table;
	}

	/**
	 * It returns an instance of IDeleter which deletes entities
	 * from the specified table.
	 *
	 * @param string $table
	 * @return IDeleter
	 * @throws NullPointerException if the $table is empty
	 */
    public static function createDeleter($table) {
		if (empty($table)) {
			throw new NullPointerException("table");
		}
		if (empty(self::$instances[$table])) {
			self::$instances[$table] = new SimpleDeleter($table);
		}
		return self::$instances[$table];
	}

	public function delete($id) {
		SimpleTableModel::createTableModel($this->table)->delete($id);
	}

}
