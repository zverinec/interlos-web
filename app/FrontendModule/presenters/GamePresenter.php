<?php
namespace FrontModule;

class GamePresenter extends BasePresenter
{

	public function renderDefault() {
		$this->setPageTitle("Zadání");
		$this->check('taskStats');
		if (\Interlos::getLoggedTeam() !== null) {
			$this->getComponent("answerHistory")->setSource(
				\Interlos::answers()->findAll()
					->where("[id_team] = %i", \Interlos::getLoggedTeam()->id_team)
					->orderBy("inserted", "DESC")
			);
			$this->getComponent("answerHistory")->setLimit(50);
		}

		$this->template->available = $this->template->available && (\Interlos::areStatsShown() || \Interlos::isAdminAccess());
	}

	public function renderHistory() {
		$this->setPageTitle("Historie odpovědí");
	}

	protected function createComponentAnswerForm() {
		return new \AnswerFormComponent($this->getUser());
	}

	protected function createComponentAnswerHistory() {
		return new \AnswerHistoryComponent();
	}

	protected function createComponentTaskStats() {
		return new \TaskStatsComponent();
	}

}
