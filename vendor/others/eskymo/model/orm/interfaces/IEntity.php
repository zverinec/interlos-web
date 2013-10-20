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
interface IEntity extends IEskymoObject, IListenable
{

	/**
	 * All modifed data
	 */
	const DATA_MODIFIED		= "modified";

	/**
	 * Data whis is not modified
	 */
	const DATA_NOT_MODIFIED	= "not_modified";

	/**
	 * All data
	 */
	const DATA_ALL			= "all";

	/**
	 * Event which describes the entity has been deleted
	 */
	const EVENT_DELETED	= "onDeleted";

	/**
	 * Event which describes the entity has been perisisted
	 */
	const EVENT_PERSISTED	= "onPersisted";

	/**
	 * The entity is new and not persisted by the inseter.
	 */
	const STATE_NEW			= "new";

	/**
	 * The entity has been already persisted, but a column has been changed.
	 */
	const STATE_MODIFIED	= "modified";

	/**
	 * The entity is persisted and the attributes and columns in database  are the same.
	 */
	const STATE_PERSISTED	= "persisted";

	/**
	 * The entity is deleted
	 */
	const STATE_DELETED		= "deleted";

	/**
	 * It adds a listener which is called after the entity is persisted
	 *
	 * @param IListener $listener
	 * @see EntityPersistedEvent
	 */
	function addOnPersistListener(IListener $listener);
	
	/**
	 * It deletes the entity
	 */
	function delete();

	/**
	 * It returns translated attribute names by the specified annotation
	 *
	 * @param string $annotation
	 * @return array
	 */
	function getAttributeNames($annotation = NULL);

	/**
	 * It returns attribute type
	 *
	 * @return array
	 * @throws NullPointerException of the $attribute is empty.
	 * @throws InvalidArgumentException if the attribute does not exist.
	 */
	function getAttributeType($attribute);

	/**
	 * It returns data whose name is translated by specified annotation.
	 * The modifier says if we want to get modified, not modified or all data.
	 *
	 * @param string $annotation
	 * @param string $modifier
	 */
	function getData($annotation = NULL, $modifier = NULL);

	/**
	 * It returns the entity ID
	 * @return int
	 */
	function getId();

	/**
	 * It returns ID key name
	 *
	 * @return string
	 */
	function getIdName();

	/**
	 * It retuturns the entity state
	 *
	 * @return string
	 */
	function getState();

	/**
	 * It loads the data from DibiRow
	 *
	 * WARNING: It deletes old data!
	 * @param array Source data
	 * @return IEntity This method is fluent.
	 */
	function loadDataFromArray(array $resource, $annotation = "Load");

	/**
	 * It persists the entity.
	 *
	 * @return IEntity
	 */
	function persist();
}

