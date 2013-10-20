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
class SimpleEntityFactory extends AEntityFactory
{

	/**
	 * Entity name
	 *
	 * @var string
	 */
	private $entityName;

	/**
	 * Instances
	 *
	 * @var array of SimpleTableFactory
	 */
	private static $instances = array();

	/**
	 * It create a new instance of the factory
	 *
	 * @param string $name Entity name
	 */
	private function  __construct($name) {
		$this->entityName = $name;
	}

	/** @return IEntity */
	public function createEmpty() {
		$entity = $this->getThisEntityName() . "Entity";
		return new $entity($this);
	}

	/**
	 * It creates a new instance of IEntityFactory
	 *
	 * @param string $name Entity name
	 * @return IEntityFactory
	 * @throws NullPointerException if the $name is empty
	 */
	public static function createEntityFactory($name) {
		if (empty($name)) {
			throw new NullPointerException("name");
		}
		$name = ucfirst($name);
		if (empty(self::$instances[$name])) {
			self::$instances[$name] = new SimpleEntityFactory($name);
		}
		return self::$instances[$name];
	}

	/* PROTECTED METHODS */

	/** @return IInserter */
	protected function createInserter() {
		$inserter = $this->getThisEntityName().'Inserter';
		if (class_exists($inserter)) {
			return $this->getInstanceOfClassByName($inserter);
		}
		else {
			return SimpleInserter::createInserter(String::lower($this->getThisEntityName()));
		}
	}

	/** @return IUpdater */
	protected function createUpdater(){
		$updater = $this->getThisEntityName().'Updater';
		if (class_exists($updater)) {
			return $this->getInstanceOfClassByName($updater);
		}
		else {
			return SimpleUpdater::createUpdater(String::lower($this->getThisEntityName()));
		}
	}

	/** @return ISelector */
	protected function createSelector(){
		return $this->getInstanceOfClassByName($this->getThisEntityName().'Selector');
	}

	/** @return IDeleter */
	protected function createDeleter() {
		$deleter = $this->getThisEntityName().'Deleter';
		if (class_exists($deleter)) {
			return $this->getInstanceOfClassByName($deleter);
		}
		else {
			return SimpleDeleter::createDeleter(String::lower($this->getThisEntityName()));
		}
	}

	/** @return string */

	private function getInstanceOfClassByName($name){
		return new $name;
	}

	protected function getThisEntityName(){
		return $this->entityName;
	}

}
