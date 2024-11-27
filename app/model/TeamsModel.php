<?php

use Dibi\DataSource;
use Dibi\Fluent;

class TeamsModel extends AbstractModel
{
	const COLLEGE = 'college';
	const HIGH_SCHOOL = 'high_school';
	const OTHER = 'other';

	const SRC_NOT_DEFINED = NULL;
	const SRC_WEB = 'web';
	const SRC_FRIENDS = 'friends';
	const SRC_FB = 'facebook';
	const SRC_IG = 'instagram';
	const SRC_PAPER = 'paper';
	const SRC_EMAIL = 'email';
	const SRC_PUZZLE = 'puzzle';

	public function find($id) {
	$this->checkEmptiness($id, "id");
	return $this->findAll()->where("[id_team] = %i", $id)->fetch();
	}

	public function findAll() {
	return $this->getConnection()->dataSource("SELECT * FROM [view_team] WHERE [id_year] = %i", Interlos::years()->findCurrent()->id_year);
	}

	/**
	 * @return DataSource
	 */
	public function findAllWithScore() {
	return $this->getConnection()->dataSource("SELECT * FROM [tmp_total_result]");
	}

	public function insert($name, $email, $category, $password, $source) {
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
		"id_year"	=> Interlos::years()->findCurrent()->id_year,
		"source"	=> $source
	))->execute();
	$return = $this->getConnection()->getInsertId();
	$this->log($return, "team_inserted", "The team [$name] has been inserted.");
	return $return;
	}

	/** @return Fluent */
	public function update(array $changes) {
	return $this->getConnection()->update("team", $changes);
	}

	public function getCategories() {
		return array(
			self::COLLEGE		=> "Vysokoškoláci",
			self::HIGH_SCHOOL => "Středoškoláci",
			self::OTHER		=> "Ostatní"
		);
	}

}
