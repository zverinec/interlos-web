<?php
/**
 * This interface is designed to be implemented by classes,
 * which represents model on the table in database.
 *
 * All classes which implement this interface should declare
 * static method 'getTable()' and declare all available columns in constants.
 *
 * The names of columns which the MySQL table of the model contains should
 * be declared by constants started by "DATA_"
 * (for example const DATA_ID = 'entity_id')/
 *
 * If the model works with MySQL view, the names of its columns should be
 * declared similary way in constants started by "VIEW_" and the class should
 * declare the static method 'getView()'.
 *
 * @author Jan Papousek
 * @see ATableModel
 */
interface ITableModel
{

	/**
	 * It deletes an entity from database.
	 *
	 * @param int $id The identificator of the entity.
	 * @throws NullPointerException if the $id is empty.
	 * @throws DataNotFoundException if the entity does not exist.
	 * @throws DibiException if there is a problem to work with database.
	 */
	function delete($id);

	/**
	 * It deletes entities based on specified condition.
	 *
	 * @param array $condition The list of columns and their values
	 * @return int Number of deleted entities.
	 * @throws InvalidArgumentException if there is a column in condition
	 *		which does not exist in MySQL table.
	 * @throws DibiDriverException if there is a problem to work with database.
	 */
	public function deleteAll(array $condition);

	/**
	 * It returns an entity based on its ID.
	 *
	 * @param int $id ID of the entity.
	 * @return DibiRow
	 * @throws NullPointerException if the $id is empty.
	 * @throws DataNotFoundException if the entity does not exist.
	 * @throws DibiException if there is a problem to work with database.
	 */
	function find($id);

	/**
	 * It returns the basic expression used to get data from database.
	 *
	 * @return DibiDataSource
	 * @throws DibiException if there is a problem to work with database.
	 */
	function findAll();

	/**
	 * It insert an entity to the database.
	 *
	 * @param array|mixed $input The input data, keys are names of the columns
	 *		and values are content.
	 * @return int Identificator of the new entity in database (or NULL if the MySQL table has no primary key)
	 *		or '-1' if the entity has already existed.
	 * @throws NullPointerException if the input is empty or does not contain
	 *		all necessary columns.
	 * @throws DibiException if there is a problem to work with database.
	 */
	function insert(array $input);

	/**
	 * It updates en entity in the database.
	 *
	 * @param int $id The identificator of the entity.
	 * @param array|mixed $input	The new data describig entity,
	 *		array keys are columns name of the table in database
	 *		and values are the content.
	 * @throws DataNotFoundException if the entity does not exist.
	 * @throws NullPointerException if $id is empty.
	 * @throws DibiException if there is a problem to work with database.
	 */
	function update($id, array $input);

	/**
	 * It updates entities based on condition.
	 *
	 * @param array $condition The list of columns and their values
	 * @param array|mixed $input	The new data describig entities,
	 *		array keys are columns name of the table in database
	 *		and values are the content.
	 * @return int Number of updated entities.
	 * @throws DibiDriverException if there is a problem to work with database.
	 */
	function updateAll(array $condition, array $input);
}

