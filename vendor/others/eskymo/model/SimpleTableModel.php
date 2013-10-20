<?php
/**
 * This factory produces simple implemtation of ITableModel.
 *
 * @author Jan Papousek
 */
class SimpleTableModel extends ATableModel
{

	/**
	 * The all instances created by factory method 'createTableModel'
	 *
	 * @var array
	 * @see SimpleTableModel::createTableMode()
	 */
	private static $instances = array();

	private $table;

	/**
	 * It creates a new instance
	 *
	 * @param string $table Table name.
	 */
	private function  __construct($table) {
		parent::__costruct();
		$this->table = $table;
	}

	/**
	 * It creates a new instance of a SimpleTableModel.
	 *
	 * @param string $table Table name.
	 * @return SimpleTableModel
	 * @throws NullPointerException if the $table is empty.
	 */
	public static function createTableModel($table) {
		if (empty($table)) {
			throw new NullPointerException("table");
		}
		if (empty(self::$instances[$table])) {
			self::$instances[$table] = new SimpleTableModel($table);
		}
		return self::$instances[$table];
	}

	protected function tableName() {
		return $this->table;
	}

}