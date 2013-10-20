<?php
/**
 * Abstract class designed to be extended by classes
 * representing model on the MySQL tables.
 *
 * The MySQL table which is represented by model extending this abstract class
 * has to have just one (numeric) primary key and all required columns
 * has to be marked as "NOT NULL".
 *
 * If the MySQL table does not use this schema, you should not extend this class,
 * but you should implement interface 'ITableModel'.
 *
 *
 * @author Jan Papousek
 * @uses ITableModel
 */
abstract class ATableModel extends /*Nette\*/Object implements ITableModel
{
	/**
	 * Names of the available columns of the table which is represeted by this model.
	 *
	 * @var array
	 */
	private $available;

	/**
	 * The primary key of the table which is represented by this model.
	 *
	 * @var DibiColumnInfo
	 */
	private $identificator;

	/**
	 * Names of the required columns of the table which is represeted by this model.
	 *
	 * @var array
	 */
	private $required;

	/**
	 * The info about this MySQL table
	 *
	 * @var DibiTableInfo
	 */
	private $tableInfo;

	/**
	 * This attribute contains relationship between MySQL data native types
	 * and dibi modifier types.
	 *
	 * @var array
	 */
	// TODO: More types
	private static $types = array(
		"int"		=> "%i",
		"text"		=> "%s",
		"varchar"	=> "%s",
		"date"		=> "%d",
		"enum"		=> "%s"
	);

	public function __costruct() {
	    //trigger_error("The class ".$this->getReflection()->getName()." is deprecated, use native dibi methods instead.", E_USER_WARNING);
	}

	/**
	 * It returns all available columns
	 *
	 * This method is probably used just by methods of this abstract class.
	 *
	 * @return array|string Names of required columns
	 */
	protected function availableColumns() {
		if (empty($this->available)) {
			$this->available = array();
			$columns = $this->getTableInfo()->getColumns();
			foreach ($columns AS $column) {
				$this->available[] = $column->getName();
			}
		}
		return $this->available;
	}

	/**
	 * It deletes an entity from database.
	 *
	 * @param int $id The identificator of the entity.
	 * @throws NullPointerException if the $id is empty.
	 * @throws DataNotFoundException if the entity does not exist.
	 * @throws DibiDriverException if there is a problem to work with database.
	 */
	public function delete($id) {
		if (empty($id)) {
			throw new NullPointerException("id");
		}
		$deleted = $this->deleteAll(array($this->identificator() => $id));
		if ($deleted < 1) {
			throw new DataNotFoundException("id");
		}
	}

	/**
	 * It deletes entities based on specified condition.
	 *
	 * @param array $condition The list of columns and their values
	 * @return int Number of deleted entities.
	 * @throws InvalidArgumentException if there is a column in condition
	 *		which does not exist in MySQL table.
	 * @throws DibiDriverException if there is a problem to work with database.
	 */
	public function deleteAll(array $condition) {
		$coumns = $this->getTableInfo()->getColumns();
		$query = dibi::delete($this->tableName());
		foreach ($coumns AS $column) {
			if (isset($condition[$column->getName()])) {
				$query->where(
					"%n = ". ExtraArray::get(self::$types, String::lower($column->getNativeType())),
					$column->getName(),
					$condition[$column->getName()]
				);
			}
		}
		return $query->execute();
	}

	/**
	 * It returns the entity from model base on its ID.
	 *
	 * @return DibiRow
	 * @throws DibiException if there is a problem to work with database.
	 */
	 public function find($id) {
		 if (empty($id)) {
			 throw new NullPointerException("id");
		 }
		 $result = $this->findAll()->where("%n = %i", $this->identificator(), $id)->fetch();
		 if (empty($result)) {
			 throw new DataNotFoundException(
				"The entity with ID '$id' does not exist in table '".$this->tableName()."'"
			 );
		 }
		 return $result;
	 }

	/**
	 * It returns the basic expression used to get data from database.
	 *
	 * @return DibiDataSource
	 * @throws DibiDriverException if there is a problem to work with database.
	 */
	public function findAll() {
		return dibi::dataSource("SELECT * FROM %n", $this->tableName());
	}

	/**
	 * It returns a table info.
	 *
	 * @return DibiTableInfo
	 * @throws DibiDriverException if there is a problem to work with database.
	 */
	protected function getTableInfo() {
		if (empty($this->tableInfo)) {
			$this->tableInfo = dibi::getDatabaseInfo()->getTable($this->tableName());
		}
		return $this->tableInfo;
	}

	/**
	 * It returns a name of column which represents an identificator
	 * of the entity.
	 *
	 * This method is probably used just by other method of this abstract class.
	 *
	 * @return string The identificator column name.
	 */
	protected function identificator() {
		if (empty($this->identificator)) {
			$primaries = $this->getTableInfo()
				->getPrimaryKey();
			if (empty($primaries)) {
				throw new NotSupportedException();
			}
			$primaries = $primaries->getColumns();
			foreach ($primaries AS $primary) {
				$this->identificator = $primary;
				break;
			}
		}
		return $this->identificator->getName();
	}

	/**
	 * It inserts an entity to the database.
	 *
	 * @param array|mixed $input The input data, keys are names of the columns
	 *		and values are content.
	 * @return int Identificator of the new entity in database (or NULL if the table has not primary key)
	 *		or '-1' if the entity has already existed.
	 * @throws NullPointerException if the input is empty or does not contain
	 *		all necessary columns.
	 * @throws DataNotFoundException if there is a foreign key on not existing entity.
	 * @throws DibiDriverException if there is a problem to work with database.
	 */
	public function insert(array $input) {
		$required = $this->requiredColumns();
		foreach ($required AS $key) {
			if (!isset($input[$key])) {
				throw new NullPointerException("input[". $key ."]");
			}
		}
		// Use just the available columns
		$toInsert = array();
		foreach ($this->availableColumns() AS $key) {
			if (isset($input[$key])) {
				$toInsert[$key] = $input[$key];
			}
		}
		try {
			$this->processQuery(dibi::insert($this->tableName(), $toInsert));
			// FIXME: Bleee
			try {
				return dibi::insertId();
			}
			catch (DibiException $e) {
				return NULL;
			}
		}
		catch (DuplicityException $e) {
			return -1;
		}
	}

	/**
	 * It executes a query and process the exceptions.
	 *
	 * @param DibiFluent
	 * @return mixed
	 * @throws DataNotFoundException if the entity does not exist
	 *		or there is the foreign key on the intity which does not exist.
	 * @throws DuplicityException if there is already the same entity in database.
	 * @throws DibiDriverException if there is a problem to work with database.
	 */
	protected function processQuery(DibiFluent $query) {
		try {
//			$query->test();
//			die();
			return $query->execute();
		}
		catch (DibiDriverException $e) {
			Debug::processException($e);
			switch ($e->getCode()) {
				case 1062:
					throw new DuplicityException();
					break;
				// FIXME: hardcoded
				case 1216:
					throw new DataNotFoundException($e->getMessage());
					break;
				default:
					throw new DibiDriverException($e->getMessage(), $e->getCode());
					break;
			}
		}
	}

	/**
	 * It returns names of all required
	 * columns of MySQl table which the model work with.
	 *
	 * This method is probably used just by methods of this abstract class.
	 *
	 * @return array|string Names of required columns
	 */
	protected function requiredColumns() {
		if (empty($this->required)) {
			$this->required = array();
			$columns = $this->getTableInfo()->getColumns();
			foreach ($columns AS $column) {
				if (!$column->isNullable() && $column->getName() != $this->identificator() && String::lower($column->getNativeType()) != "timestamp") {
					$this->required[] = $column->getName();
				}
			}
		}
		return $this->required;
	}

	/**
	 * It returns a name of the MySQL table which the model work on.
	 *
	 * This method is probably used just by methods of this abstract class.
	 *
	 * @return string Table name
	 */
	protected function tableName() {
		if (!method_exists($this->getReflection->getName(), "getTable")) {
			throw new NotImplementedException("The class '".$this->getReflection->getName()."' extends 'ATableModel' and has to implement static method 'getTable()'.");
		}
		return call_user_func_array(array($this->getReflection->getName(), "getTable"), $args = array());
	}

	/**
	 * It updates en entity in the database.
	 *
	 * @param int $id The identificator of the entity.
	 * @param array|mixed $input	The new data describig entity,
	 *		array keys are columns name of the table in database
	 *		and values are the content.
	 * @throws NullPointerException if $id is empty.
	 * @throws DataNotFoundException if the entity does not exist
	 *		or there is the foreign key on the intity which does not exist.
	 * @throws DuplicityException if there is already the same entity in database.
	 * @throws DibiDriverException if there is a problem to work with database.
	 */
	public function update($id, array $input) {
		if (empty($id)) {
			throw new NullPointerException("id");
		}
		$rows = $this->findAll()->where("%n = %i", $this->identificator(), $id);
		if ($rows->count() == 0) {
			throw new DataNotFoundException("id");
		}
		$result = $this->updateAll(array($this->identificator() => $id), $input);
		return ($result == 0) ? FALSE : TRUE;
	}

	/**
	 * It updates entities based on condition.
	 *
	 * @param array $condition The list of columns and their values
	 * @param array|mixed $input	The new data describig entities,
	 *		array keys are columns name of the table in database
	 *		and values are the content.
	 * @return int Number of updated entities.
	 * @throws NullPointerException if some of the required columns is set, but empty.
	 * @throws DuplicityException if there is already the same entity in database.
	 * @throws DibiDriverException if there is a problem to work with database.
	 */
	public function updateAll(array $condition, array $input) {
		$coumns = $this->getTableInfo()->getColumns();
		// Check validity
		foreach ($this->requiredColumns() AS $column) {
			if (key_exists($column, $input) && empty($input[$column])) {
				throw new NullPointerException("input[$column]");
			}
		}
		// Use just the available columns
		$toUpdate = array();
		foreach ($this->availableColumns() AS $key) {
			if (isset($input[$key])) {
				$toUpdate[$key] = $input[$key];
			}
		}
		$query = dibi::update($this->tableName(), $toUpdate);
		// Create condition
		foreach ($coumns AS $column) {
			if (isset($condition[$column->getName()])) {
				$query->where(
					"%n = ". ExtraArray::get(self::$types, $column->getNativeType()),
					$column->getName(),
					$condition[$column->getName()]
				);
			}
		}

		return $this->processQuery($query);
	}

}
