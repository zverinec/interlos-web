<?php

use Dibi\DataSource;

class ScoreModel extends AbstractModel {
    public function find($id) {
        throw new Nette\NotSupportedException();
    }


    public function findAll() {
        throw new Nette\NotSupportedException();
    }

    /** @return DataSource */
    public function findAllBonus() {
        return $this->getConnection()->dataSource("SELECT * FROM [tmp_bonus]");
    }

    /** @return DataSource */
    public function findAllTasks() {
        return $this->getConnection()->dataSource("SELECT * FROM [tmp_task_result]");
    }

    /** @return DataSource */
    public function findAllPenality() {
        return $this->getConnection()->dataSource("SELECT * FROM [tmp_penality]");
    }
}
