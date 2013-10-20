<?php
final class InterlosTemplate
{

    final private function  __construct() {}

    public static function loadTemplate(Nette\Templating\ITemplate $template) {
	// register custom helpers
	$template->registerHelper("date", Helpers::getHelper('date'));
	$template->registerHelper("time", Helpers::getHelper('time'));
	$template->registerHelper("timeOnly", Helpers::getHelper('timeOnly'));
	$template->registerHelper("texy", Helpers::getHelper('texy'));

	return $template;
    }
}
