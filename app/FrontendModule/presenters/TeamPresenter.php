<?php
namespace FrontModule;

class TeamPresenter extends BasePresenter
{

	public function renderDefault()
	{
		$this->setPageTitle(\Interlos::getLoggedTeam()->name);
	}

	public function renderList()
	{
		$this->setPageTitle("Seznam týmů");
		$this->getComponent("teamList")->setSource(
			\Interlos::teams()->findAll()
		);
		$this->getTemplate()->categories = array(
			\TeamsModel::HIGH_SCHOOL => "Středoškoláci",
			\TeamsModel::COLLEGE => "Vysokoškoláci",
			\TeamsModel::OTHER => "Ostatní",
		);
	}

	public function renderRegistration()
	{
		$this->setPageTitle("Registrace");
	}

	// ---- PROTECTED METHODS

	protected function createComponentTeamForm($name)
	{
		return new \TeamFormComponent($this, $name);
	}

	protected function createComponentTeamList($name)
	{
		return new \TeamListComponent($this, $name);
	}
}
