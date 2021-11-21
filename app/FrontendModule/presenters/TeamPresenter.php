<?php
namespace FrontModule;

use Nette\Mail\IMailer;

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
			\Interlos::teams()->findAll()->orderBy("name")
		);
		$this->getTemplate()->categories = array(
			\TeamsModel::HIGH_SCHOOL => "Středoškoláci",
			\TeamsModel::COLLEGE => "Vysokoškoláci",
			\TeamsModel::OTHER => "Ostatní",
		);
		$this->check("results");

		$this->template->available = $this->template->available && (\Interlos::areStatsShown() || \Interlos::isAdminAccess());
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
		$comp = new \TeamFormComponent($this->getUser(), $this->context->getByType(IMailer::class));
		$comp->setMailParameters($this->context->parameters['mail']);
		return $comp;
	}

	protected function createComponentResults($name) {
		return new \ResultsComponent();
	}

	protected function createComponentTeamList($name)
	{
		return new \TeamListComponent();
	}
}
