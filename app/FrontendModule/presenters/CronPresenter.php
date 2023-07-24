<?php
namespace FrontModule;

class CronPresenter extends BasePresenter
{

	public function actionDatabase($key): never {
		if(\Interlos::resetTemporaryTables()) {
			print "TEMPORARY TABLES: CREATED";
		} else {
			print "TEMPORARY TABLES: PERMISSION DENIED";
		}
		$this->terminate();
	}

}
