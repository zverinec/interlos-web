<?php
namespace FrontModule;

class CronPresenter extends BasePresenter
{

	public function renderDatabase($key) {
		Interlos::resetTemporaryTables();
	}

	protected function startup() {
		parent::startup();
		if ($this->getParameter("key") != $this->params['keys']['cron']->key) {
			die("PERMISSION DENIED");
		}
	}

}
