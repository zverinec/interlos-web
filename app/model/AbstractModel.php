<?php
abstract class AbstractModel implements InterlosModel {

	private $connection;

	public function  __construct(DibiConnection $connection) {
		$this->connection = $connection;
	}

	// ----- PROTECTED METHODS

	protected final function checkEmptiness($var, $name) {
		if (empty($var)) {
			throw new NullPointerException("The parameter [$name] is empty.");
		}
	}

	/** @return DibiConnection */
	protected final function getConnection() {
		return $this->connection;
	}

	protected final function log($team, $type, $text) {
		try {
			$this->getConnection()->insert("log", array(
					"id_team"	=> $team,
					"type"	=> $type,
					"text"	=> $text,
					"inserted"	=> new DateTime()
					))->execute();
		}
		catch(Exception $e) {
			Nette\Diagnostics\Debugger::processException($e);
		}
	}

}
