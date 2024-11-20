<?php
class CompetitorsModel extends AbstractModel {

    public function deleteByTeam($team) {
        $this->checkEmptiness($team, "team");
        $this->getConnection()->delete("competitor")
                ->where("[id_team] = %i", $team)
                ->execute();
        $this->log($team, "competitors_deleted", "The competitors of team [$team] have been deleted.");
    }

    public function find($id) {
        $this->checkEmptiness($id, "id");
        return $this->findAll()->where("[id_competitor] = %i", $id)->fetch();
    }

    public function findAll() {
        return $this->getConnection()->dataSource("SELECT * FROM [view_competitor]");
    }
    
    public function findCurrentYearTeamCount() {
        return $this->getConnection()
                ->select("id_team")
                ->select("COUNT(id_competitor) AS competitors")
                ->from("view_competitor")
                ->where("[id_team] IN (SELECT id_team FROM [view_team])")
                ->groupBy("id_team")
                ->fetchPairs("id_team", "competitors");
    }

    public function findAllByTeam($team) {
        $this->checkEmptiness($team, "team");
        return $this->findAll()->where("[id_team] = %i", $team);
    }

    public function insert($team, $school, $name) {
        $this->checkEmptiness($team, "team");
        $this->checkEmptiness($name, "name");
        $return = $this->getConnection()->insert("competitor", array(
                "id_team"    => $team,
                "id_school"    => $school === '' ? null : $school,
                "name"    => $name,
                "inserted" => new DateTime()
                ))->execute();
        $this->log($team, "competitor_inserted", "The new competitor [$name] has been inserted and joined to team.");
        return $return;
    }
}
