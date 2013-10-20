<?php
namespace FrontModule;

class StatsPresenter extends BasePresenter
{

	public function renderDefault() {
		$this->setPageTitle("Výsledky");
		$this->check("results");
	}

	public function renderDetail() {
		$this->setPageTitle("Podrobné výsledky");
		$this->check("scoreList");
	}

	public function renderTasks() {
		$this->setPageTitle("Statistika úkolů");
		$this->check("taskStats");
	}

	protected function createComponentResults($name) {
		return new \ResultsComponent($this, $name);
	}

	protected function createComponentScoreList($name) {
		return new \ScoreListComponent($this, $name);
	}

	protected function createComponentTaskStats($name) {
		return new \TaskStatsComponent($this, $name);
	}

	private function check($componentName) {
		try {
			$this->getComponent($componentName);
			$this->getTemplate()->available = TRUE;
		}
		catch(Exception $e) {
			$this->flashMessage("Statistiky jsou momentálně nedostupné. Pravděpodobně dochází k přepočítávání.", "error");
			$this->getTemplate()->available = FALSE;
		}
	}

}
