<?php

use Nette\Routing\Router;
use Nette\StaticClass;


final class RouterFactory
{
	use StaticClass;

	public static function createRouter(): Router
	{
		return FrontModule::createRouter("Front");
	}
}
