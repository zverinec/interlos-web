<?php

namespace FrontModule;

use Nette,
	Model;
use Tracy\Debugger;


/**
 * Error presenter.
 */
class ErrorPresenter extends BasePresenter
{

	/**
	 * @param  Exception
	 * @return void
	 */
	public function renderDefault($exception) {
		if ($exception instanceof Nette\Application\BadRequestException) {
			$code = $exception->getCode();
			$this->setView(in_array($code, array(403, 404, 500)) ? $code : '500');
			Debugger::log("HTTP code $code: {$exception->getMessage()} in {$exception->getFile()}:{$exception->getLine()}", 'access');
		} else {
			$this->setView('500'); // load template 500.latte
			Debugger::log($exception, Debugger::ERROR); // and log exception
		}
	}

}