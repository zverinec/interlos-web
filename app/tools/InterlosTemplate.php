<?php

use Nette\Application\UI\Template as UITemplate;
use Nette\Bridges\ApplicationLatte\Template;

final class InterlosTemplate
{

	final private function  __construct() {}

	public static function loadTemplate( $template) {
		if (!$template instanceof Template && !$template instanceof UITemplate) {
			throw new InvalidArgumentException('Template of wrong class given.');
		}
		// register custom helpers
		$template->getLatte()->addFilter("date", Helpers::getHelper('date'));
		$template->getLatte()->addFilter("time", Helpers::getHelper('time'));
		$template->getLatte()->addFilter("timeOnly", Helpers::getHelper('timeOnly'));
		$template->getLatte()->addFilter("texy", Helpers::getHelper('texy'));

		return $template;
	}
}
