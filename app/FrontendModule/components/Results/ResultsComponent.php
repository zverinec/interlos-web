<?php
class ResultsComponent extends BaseComponent
{

	protected function startUp() {
		$this->getTemplate()->teams = Interlos::teams()
			->findAllWithScore()
			->fetchAssoc("category,id_team");

		$this->getTemplate()->categories = array(
				TeamsModel::HIGH_SCHOOL => "Středoškoláci",
				TeamsModel::COLLEGE		=> "Vysokoškoláci",
				TeamsModel::OTHER		=> "Ostatní",
		);

		$this->getTemplate()->bonus = Interlos::score()
			->findAllBonus()
			->fetchAssoc("id_team");
		$this->getTemplate()->penality = Interlos::score()
			->findAllPenality()
			->fetchAssoc("id_team");
	}

}
