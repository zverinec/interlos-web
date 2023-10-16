<?php
namespace FrontModule;

use Nette\DI\Attributes\Inject;
use Nette\Mail\Mailer;

class TeamPresenter extends BasePresenter
{

	#[Inject]
	public Mailer $mailer;

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

	protected function createComponentTeamForm()
	{
		$comp = new \TeamFormComponent($this->getUser(), $this->mailer);
		$comp->setMailParameters($this->mailParameters);
		return $comp;
	}

	protected function createComponentResults() {
		return new \ResultsComponent();
	}

	protected function createComponentTeamList()
	{
		return new \TeamListComponent();
	}
}
