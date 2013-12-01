<?php
class TeamsModel extends AbstractModel
{

    const COLLEGE	= 'college';

    const HIGH_SCHOOL	= 'high_school';

    const OTHER		= 'other';

    public function find($id) {
	$this->checkEmptiness($id, "id");
	return $this->findAll()->where("[id_team] = %i", $id)->fetch();
    }

    public function findAll() {
	return $this->getConnection()->dataSource("SELECT * FROM [view_team] WHERE [id_year] = %i", Interlos::years()->findCurrent()->id_year);
    }

    /**
     * @return DibiDataSource
     */
    public function findAllWithScore() {
	return $this->getConnection()->dataSource("SELECT * FROM [tmp_total_result]");
    }

    public function insert($name, $email, $category, $password) {
	$this->checkEmptiness($name, "name");
	$this->checkEmptiness($email, "email");
	$this->checkEmptiness($category, "category");
	$this->checkEmptiness($password, "password");
	$password = TeamAuthenticator::passwordHash($password);
	$this->getConnection()->insert("team", array(
	    "name"	=> $name,
	    "email"	=> $email,
	    "category"	=> $category,
	    "password"	=> $password,
	    "inserted"	=> new DateTime(),
	    "id_year"	=> Interlos::years()->findCurrent()->id_year
	))->execute();
	$return = $this->getConnection()->insertId();
	$this->log($return, "team_inserted", "The team [$name] has been inserted.");
	return $return;
    }

    /** @return DibiFluent */
    public function update(array $changes) {
	return $this->getConnection()->update("team", $changes);
    }
    
	public function getCategories() {
		return array(
			self::COLLEGE	    => "Vysokoškoláci",
			self::HIGH_SCHOOL => "Středoškoláci",
			self::OTHER	    => "Ostatní"
		);
	}

}
