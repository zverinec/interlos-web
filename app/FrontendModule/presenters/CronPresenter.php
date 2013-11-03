<?php
namespace FrontModule;

class CronPresenter extends BasePresenter
{

	public function actionDatabase($key) {
		if(\Interlos::resetTemporaryTables()) {
			print "TEMPORARY TABLES: CREATED";
		} else {
			print "TEMPORARY TABLES: PERMISSION DENIED";
		}
		$this->terminate();
	}

}
