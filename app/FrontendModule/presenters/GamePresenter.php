<?php
namespace FrontModule;

class GamePresenter extends BasePresenter
{

	public function renderDefault() {
		$this->setPageTitle("Zadání");
		$this->check('taskStats');
		$this->getComponent("answerHistory")->setSource(
			\Interlos::answers()->findAll()
				->where("[id_team] = %i", \Interlos::getLoggedTeam()->id_team)
				->orderBy("inserted", "DESC")
		);
		$this->getComponent("answerHistory")->setLimit(50);
	}

	public function renderHistory() {
		$this->setPageTitle("Historie odpovědí");
	}

	protected function startUp() {
		parent::startUp();
		if (\Interlos::getLoggedTeam() == null) {
			$this->flashMessage("Do této sekce mají přístup pouze přihlášené týmy.", "error");
			$this->redirect("Default:default");
		}
	}

	protected function createComponentAnswerForm($name) {
		return new \AnswerFormComponent($this, $name);
	}

	protected function createComponentAnswerHistory($name) {
		return new \AnswerHistoryComponent($this, $name);
	}

	protected function createComponentTaskStats($name) {
		return new \TaskStatsComponent($this, $name);
	}

}
