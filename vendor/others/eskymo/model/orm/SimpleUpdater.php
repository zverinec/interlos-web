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
class SimpleUpdater implements IUpdater
{

	/**
	 * Available instances
	 *
	 * @var array of SimpleUpdater
	 */
	private static $instances = array();

	/**
	 * Table which the updater works with
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
	 * It returns an instance of IUpdater which updates entities
	 * in the specified table.
	 *
	 * @param string $table
	 * @return IUpdater
	 * @throws NullPointerException if the $table is empty
	 */
    public static function createUpdater($table) {
		if (empty($table)) {
			throw new NullPointerException("table");
		}
		if (empty(self::$instances[$table])) {
			self::$instances[$table] = new SimpleUpdater($table);
		}
		return self::$instances[$table];
	}

	public function update(IEntity $entity) {
		if ($entity->getState() != IEntity::STATE_MODIFIED) {
			throw new InvalidArgumentException("The entity can not be inserted because it is not in state [MODIFIED].");
		}
		return SimpleTableModel::createTableModel($this->table)
			->update($entity->getId(), $entity->getData("Save"));
	}

}
