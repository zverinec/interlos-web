<?php
namespace FrontModule;

class StatsPresenter extends BasePresenter
{

	public function renderDefault() {
		$this->setPageTitle("Podrobné výsledky");
		$this->check("scoreList");
	}

	protected function createComponentScoreList($name) {
		return new \ScoreListComponent($this, $name);
	}

}
