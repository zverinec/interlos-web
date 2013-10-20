<?php

class EntityGenerator
{

	/** @var DibiConnection	*/
	private $connection;

	/** @var Template */
	private $template;

	/* PUBLIC METHODS */

	public function  __construct(DibiConnection $connection) {
		$this->connection = $connection;
	}

	/**
	 * It returns a string which represents an entity source code.
	 *
	 * @param string $table
	 */
	public function generate($table) {
		$this->getTemplate()->entityName = $this->getEntityName($table);
		$this->getTemplate()->attributes = $this->getAttributes($table);
		$this->getTemplate()->translatedId = $this->getPrimaryKeyName($table);
		echo $this->getTemplate()->__toString();
	}

	/* PRIVATE METHODS */	

	private function getAttribute(DibiColumnInfo $column) {
		$translated = ExtraString::lowerFirst(
			strtr(
				String::capitalize($column->getName()),
				array("_" => "")
		));
		$attribute = array("name" => $translated);
		if ($translated != $column->getName()) {
			$attribute["translate"] = $column->getName();
		}
		$attribute["rules"] = array();
		if (!$column->isNullable()) {
			$attribute["rules"][] = array("type"	=> "filled");
		}
		if (String::lower($column->getNativeType()) == "text") {
			$attribute["form"] = array(
				"withResource"	=> "textarea"
			);
		}
		elseif(String::lower($column->getNativeType()) == "enum") {
			$attribute["type"] = array(
				"name"	=> "enum"
			);
			// TODO: values
		}
		return $attribute;
	}

	private function getAttributes($table) {
		$columns	= $this->connection->getDatabaseInfo()->getTable($table)->getColumns();
		$primary	= $this->getPrimaryKeyName($table);
		$result		= array();
		foreach ($columns AS $column) {
			if ($column->getName() != $primary) {
				$result[] = $this->getAttribute($column);
			}
		}
		return $result;
	}

	protected function getEntityName($table) {
		return strtr(
				String::capitalize($table),
				array("_" => "")
		) . "Entity";
	}

	private function getPrimaryKeyName($table) {
		$primaryKeys = $this->connection->getDatabaseInfo()->getTable($table)->getPrimaryKey()->getColumns();
		if (sizeof($primaryKeys) != 1) {
			throw new InvalidStateException("The table [$table] must have 1 primary key column.");
		}
		$primaryKey = ExtraArray::firstValue($primaryKeys);
		return $primaryKey->getName();
	}

	/** @return Template */
	private function getTemplate() {
		if (empty($this->template)) {
			$this->template = new Template();
			$this->template->registerFilter('CurlyBracketsFilter::invoke');
			$this->template->setFile(dirname(__FILE__) . "/entity.phtml");
		}
		return $this->template;
	}

}
