<?php
use Nette\Bridges\ApplicationLatte\Template;

final class InterlosTemplate
{

	final private function  __construct() {}

    public static function loadTemplate( $template) {
		if (!$template instanceof Template && !$template instanceof \Nette\Templating\ITemplate) {
			throw new InvalidArgumentException('Template of wrong class given.');
		}
		// register custom helpers
		$template->registerHelper("date", Helpers::getHelper('date'));
		$template->registerHelper("time", Helpers::getHelper('time'));
		$template->registerHelper("timeOnly", Helpers::getHelper('timeOnly'));
		$template->registerHelper("texy", Helpers::getHelper('texy'));

		return $template;
	}
}
