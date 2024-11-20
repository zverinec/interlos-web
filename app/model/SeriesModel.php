<?php

use Dibi\DataSource;

class SeriesModel extends AbstractModel {
    public function find($id) {
        $this->checkEmptiness($id, "id");
        return $this->findAll()->where("[id_serie] = %i", $id)->fetch();
    }

    /**
     * @return DataSource
     */
    public function findAll() {
        return $this->getConnection()->dataSource("SELECT * FROM [view_serie]");
    }

    /**
     * @return DataSource
     */
    public function findAllAvailable() {
        return $this->getConnection()->dataSource("SELECT * FROM [view_serie] WHERE [to_show] < NOW() ORDER BY [id_serie]");
    }
}
