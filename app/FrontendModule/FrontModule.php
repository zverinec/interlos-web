<?php

use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;

class FrontModule {

    public static function createRouter($prefix) {
        $routeList = new RouteList($prefix);
        $routeList[] = new Route('<presenter>/<action>', 'Default:default');
        return $routeList;
    }

}
