<?php
class AnswersModel extends AbstractModel {

	const ERROR_TIME_LIMIT = 10;

	public function find($id) {
		$this->checkEmptiness($id, "id");
		return $this->findAll()->where("[id_answer] = %i", $id)->fetch();
	}

	/**
	 * @return DibiDataSource
	 */
	public function findAll() {
		return $this->getConnection()->dataSource("SELECT * FROM [view_answer]");
	}

	public function findAllCorrect($team = NULL) {
		$source = $this->getConnection()->dataSource("SELECT * FROM [view_correct_answer]");
		if (!empty($team)) {
			$source->where("[id_team] = %i", $team);
		}
		return $source;
	}

	public function insert($team, $task, $code) {
		$this->checkEmptiness($team, "team");
		$this->checkEmptiness($task, "task");
		//$this->checkEmptiness($code, "code");
		// Correct answers of the team
		$correctAnswers = $this->findAllCorrect($team)
				->fetchPairs("id_answer", "id_answer");
		// Last answer has to be older than 30 seconds
		$query = $this->findAll()
				->where("[id_team] = %i", $team)
				->where("[inserted] > NOW() - INTERVAL 30 SECOND");
		if (!empty($correctAnswers)) {
			$query->where("[id_answer] NOT IN %l", $correctAnswers);
		}
		$lastInTimeLimit = $query->count();
		// Check it
		if ($lastInTimeLimit != 0) {
			$this->log($team, "solution_tried", "The team tried to insert the solution of task [$task] with code [$code].");
			throw new Nette\InvalidStateException("There is a wrong answer in recent 30 seconds.", self::ERROR_TIME_LIMIT);
		}
		// Insert a new answer
		$return = $this->getConnection()->insert("answer", array(
				"id_team"	=> $team,
				"id_task"	=> $task,
				"code"	=> $code,
				"inserted"	=> new DateTime()
				))->execute();
		// Log the action
		$this->log($team, "solution_inserted", "The team successfuly inserted the solution of task [$task] with code [$code].");
		return $return;
	}

}
