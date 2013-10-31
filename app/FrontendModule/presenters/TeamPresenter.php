<?php
namespace FrontModule;

class TeamPresenter extends BasePresenter
{

	public function renderDefault()
	{
		if(!$this->user->isLoggedIn()) {
			$this->redirect('Default:login');
		}
		$this->setPageTitle(\Interlos::getLoggedTeam()->name);
	}

	public function actionList()
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
		$this->check("results");
	}

	public function actionRegistration()
	{
		if($this->user->isLoggedIn()) {
			$this->redirect('default');
		}
		if(!\Interlos::isRegistrationActive()) {
			$this->flashMessage('Momentálně neprobíhá registrace.', 'error');
		}
	}

	public function renderRegistration()
	{
		$this->setPageTitle("Registrace");
	}

	// ---- PROTECTED METHODS

	protected function createComponentTeamForm($name)
	{
		$comp = new \TeamFormComponent($this, $name);
		$comp->setMailParameters($this->context->parameters['mail']);
		return $comp;
	}

	protected function createComponentResults($name) {
		return new \ResultsComponent($this, $name);
	}

	protected function createComponentTeamList($name)
	{
		return new \TeamListComponent($this, $name);
	}
}
