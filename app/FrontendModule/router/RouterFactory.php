<?php

use Nette\Application\IRouter;
use Nette\StaticClass;


final class RouterFactory
{
    use StaticClass;

    /**
     * @return IRouter
     */
    public static function createRouter()
    {
        return FrontModule::createRouter("Front");
    }
}
