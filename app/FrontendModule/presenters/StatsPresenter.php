<?php
namespace FrontModule;

class StatsPresenter extends BasePresenter
{

	public function renderDefault() {
		$this->setPageTitle("Podrobné výsledky");
		$this->check("scoreList");
		if (!\Interlos::areStatsShown() && !\Interlos::isAdminAccess()) {
			$this->flashMessage('Pořadí týmů není v posledních 30 minutách hry k dispozici', 'error');
		}
		$this->template->available = $this->template->available && (\Interlos::areStatsShown() || \Interlos::isAdminAccess());
	}

	protected function createComponentScoreList() {
		return new \ScoreListComponent();
	}

}
