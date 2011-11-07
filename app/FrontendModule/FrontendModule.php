<?php
class FrontendModule {

	public static function createRouter($prefix = "/") {
//		$router = new MultiRouter();
//
//		$router[] = new Route($prefix . "<presenter>/<action>", array(
//						"module"		=> "frontend",
//						"presenter"		=> "default",
//						"action"		=> "default"
//		));
//
//		return $router;
        return new SimpleRouter(array(SimpleRouter::PRESENTER_KEY => "default", SimpleRouter::MODULE_KEY => "frontend"));
	}

	public static function getModuleDir() {
		return APP_DIR . "/FrontendModule";
	}

}

