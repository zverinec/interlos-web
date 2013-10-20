<?php
class ScoreModel extends AbstractModel {
	public function find($id) {
		throw new Nette\NotSupportedException();
	}


	public function findAll() {
		throw new Nette\NotSupportedException();
	}

	/** @return DibiDataSource */
	public function findAllBonus() {
		return $this->getConnection()->dataSource("SELECT * FROM [tmp_bonus]");
	}

	/** @return DibiDataSource */
	public function findAllTasks() {
		return $this->getConnection()->dataSource("SELECT * FROM [tmp_task_result]");
	}

	/** @return DibiDataSource */
	public function findAllPenality() {
		return $this->getConnection()->dataSource("SELECT * FROM [tmp_penality]");
	}
}
