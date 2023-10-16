<?php

use Dibi\Row;
use Nette\DI\Container;
use Dibi\Connection;
use Nette\Security\User;

class Interlos {

	/** @var Container */
	private static $container;

    private static $adminMessages = FALSE;

	private static $currentYear;

	private static $loggedTeam;


	public static function injectContainer(Container $container) {
		self::$container = $container;
	}

	/** @return AnswersModel */
	public static function answers() {
		return self::getModel("answers");
	}

	/** @return ChatModel */
	public static function chat() {
		return self::getModel("chat");
	}

	/** @return CompetitorsModel */
	public static function competitors() {
		return self::getModel("competitors");
	}

    public static function createAdminMessages() {
        if (self::$adminMessages) {
            return;
        }
        /** @var \Nette\Application\Application $application */
        $application = self::$container->getByType(\Nette\Application\Application::class);
        $presenter = $application->getPresenter();
        if (self::isAdminAccess()) {
                $presenter->flashMessage("Přístup administrátora schválen.", "info");
        }
        if (self::loadAdminProperty("game-end") !== NULL) {
            $presenter->flashMessage("Konec hry nastaven na ". (self::loadAdminProperty("game-end") ? "TRUE" : "FALSE") .".");
        }
        if (self::loadAdminProperty("game-started") !== NULL) {
            $presenter->flashMessage("Začátek hry nastaven na ". (self::loadAdminProperty("game-started") ? "TRUE" : "FALSE") .".");
        }
        if (self::loadAdminProperty("registration-end") !== NULL) {
            $presenter->flashMessage("Konec registrace nastaven na ". (self::loadAdminProperty("registration-end") ? "TRUE" : "FALSE") .".");
        }
        if (self::loadAdminProperty("registration-started") != NULL) {
            $presenter->flashMessage("Začátek registrace nastaven na ". (self::loadAdminProperty("registration-started") ? "TRUE" : "FALSE") .".");
        }
        if (self::loadAdminProperty("time") !== NULL) {
            $presenter->flashMessage("Herní čas nastaven na ". self::loadAdminProperty("time") .".");
        }
        self::$adminMessages = TRUE;
    }

	/** @return \Dibi\Connection */
	public static function getConnection() {
		return self::$container->getByType(Connection::class);
	}

	/** @return Row */
	public static function getCurrentYear() {
		if (!isset(self::$currentYear)) {
			self::$currentYear = self::years()->findCurrent();
		}
		return self::$currentYear;
	}

	/** @return Row */
	public static function getLoggedTeam() {
		if (!isset(self::$loggedTeam)) {
			if (self::getUser()->isLoggedIn()) {
				self::$loggedTeam = Interlos::teams()->find(self::getUser()->getIdentity()->id_team);
			}
			else {
				self::$loggedTeam = NULL;
			}
		}
		return self::$loggedTeam;
	}

	public static function getUser() {
		return self::$container->getByType(User::class);
	}

    public static function isAdminAccess() {
        return isset($_GET["admin-key"]) && self::$container->parameters['keys']['admin'] == $_GET["admin-key"];
    }

    public static function isCronAccess() {
        return isset($_GET["cron-key"]) && self::$container->parameters['keys']['cron'] == $_GET["cron-key"];
    }

    public static function isGameActive() {
        return self::isGameStarted() && !self::isGameEnd();
    }

    public static function isGameEnd() {
        if (self::loadAdminProperty("game-end")) {
            return self::loadAdminProperty("game-end");
        }
        else {
            return self::getCurrentTime() > strtotime(Interlos::getCurrentYear()->game_end);
        }
    }

    public static function isGameStarted() {
        if (self::loadAdminProperty("game-started") !== null) {
            return self::loadAdminProperty("game-started");
        }
        else {
            return strtotime(Interlos::getCurrentYear()->game_start) < self::getCurrentTime();
        }
    }

	public static function areStatsShown() {
		if (self::loadAdminProperty("game-show-stats") !== null) {
			return self::loadAdminProperty("game-show-stats");
		}
		else {
			// Disable statistics 30 minutes before end of game
			return (self::getCurrentTime() < (strtotime(Interlos::getCurrentYear()->game_end) - (30 * 60)))
				|| (self::getCurrentTime() > strtotime(Interlos::getCurrentYear()->game_end));
		}
	}

    public static function isRegistrationActive() {
        return self::isRegistrationStarted() && !self::isRegistrationEnd();
    }

    public static function isRegistrationEnd() {
        if (self::loadAdminProperty("registration-end")) {
            return self::loadAdminProperty("game-end");
        }
        else {
            return strtotime(Interlos::getCurrentYear()->registration_end) < self::getCurrentTime();
        }
    }

    public static function isRegistrationStarted() {
        if (self::loadAdminProperty("registration-started")) {
            return self::loadAdminProperty("game-started");
        }
        else {
            return strtotime(Interlos::getCurrentYear()->registration_start) < self::getCurrentTime();
        }
    }

    public static function prepareAdminProperties() {
        if (!self::isAdminAccess()) {
            return;
        }
        $propertiesToStore = array("game-end", "game-started", "registration-end", "registration-started", "time");
        $session = self::getSessionNamespace("admin.property");
        if (self::isAdminPropertyAvailableInURL("reset-admin-properties")) {
            foreach($propertiesToStore as $property) {
                $session[$property] = NULL;
            }
        }
        foreach($propertiesToStore as $property) {
            if (self::isAdminPropertyAvailableInURL($property)) {
                self::storeAdminProperty($property);
            }
        }
    }

	/**
	 * Beware, use only in CronPresenter!
	 * It has performance problems
	 * @return bool
	 */
	public static function resetTemporaryTables() {
		if(!self::isCronAccess() && !self::isAdminAccess()) { // Cron or admin code must be provided in URL
           	return false;
        }
		self::getConnection()->begin();
		// Total results
		self::getConnection()->query("DROP TABLE IF EXISTS [tmp_total_result]");
		self::getConnection()->query("CREATE TABLE [tmp_total_result] AS SELECT * FROM [view_total_result]");
		// Task results
		self::getConnection()->query("DROP TABLE IF EXISTS [tmp_task_result]");
		self::getConnection()->query("CREATE TABLE [tmp_task_result] AS SELECT * FROM [view_task_result]");
		// Task statistics
		self::getConnection()->query("DROP TABLE IF EXISTS [tmp_task_stat]");
		self::getConnection()->query("CREATE TABLE [tmp_task_stat] AS SELECT * FROM [view_task_stat]");
		// Correct answers
		self::getConnection()->query("DROP TABLE IF EXISTS [tmp_correct_answer]");
		self::getConnection()->query("CREATE TABLE [tmp_correct_answer] (INDEX([id_team]),INDEX([id_task])) AS SELECT * FROM [view_correct_answer]");
		// Penalities
		self::getConnection()->query("DROP TABLE IF EXISTS [tmp_penality]");
		self::getConnection()->query("CREATE TABLE [tmp_penality] AS SELECT * FROM [view_penality]");
		// Bonuses
		self::getConnection()->query("DROP TABLE IF EXISTS [tmp_bonus]");
		self::getConnection()->query("CREATE TABLE [tmp_bonus] AS SELECT * FROM [view_bonus]");
		self::getConnection()->commit();
		return true;
	}

	/** @return SchoolsModel */
	public static function schools() {
		return self::getModel("schools");
	}

	/** @return ScoreModel */
	public static function score() {
		return self::getModel("score");
	}

	/** @return TasksModel */
	public static function tasks() {
		return self::getModel("tasks");
	}

	/** @return TeamsModel */
	public static function teams() {
		return self::getModel("teams");
	}

	/** @return YearsModel */
	public static function years() {
		return self::getModel("years");
	}

	// ---- PRIVATE METHODS

	private static function createModel($name) {
		$className = ucfirst($name) . "Model";
		// Check whether the model class exist
		if (!class_exists($className)) {
			throw  new Nette\InvalidStateException("The class [$className] does not exists.");
		}
		// Check whether the class is really the model class
		$key = ExtraArray::keysOf(class_implements($className), "InterlosModel");
		if (empty($key)) {
			throw new Nette\InvalidStateException("The class [$className] does not implement interface [InterlosModel]");
		}
		// Return new instance of model class
		return new $className(self::getConnection());
	}

    public static function getCurrentTime() {
        if (self::loadAdminProperty("time") !== NULL) {
            return strtotime(self::loadAdminProperty("time"));
        }
        else {
            return time();
        }
    }

	private static function getModel($name) {
		$className = $name . "Model";
		return self::$container->getService($className);
	}

    private static function getAdminPropertyValueFromURL($property) {
        if (!self::isAdminAccess()) {
            return false;
        }
        if (!isset($_GET[$property])) {
            return false;
        }
        $trues = array("1", "TRUE", "true", "yes", "YES");
        $falses = array("0", "FALSE", "false", "no", "NO");
        if (in_array($_GET[$property], $trues)) {
            return true;
        }
        else if (in_array($_GET[$property], $falses)) {
            return false;
        }
        else {
            return $_GET[$property];
        }
    }

    private static function isAdminPropertyAvailableInURL($property) {
        if (!self::isAdminAccess()) {
            return false;
        }
        if (!isset($_GET[$property])) {
            return false;
        }
        return true;
    }

    private static function loadAdminProperty($property) {
        $session = self::getSessionNamespace("admin.property");
        if (isset($session[$property])) {
            return $session[$property];
        }
        else {
            return NULL;
        }
    }

    private static function storeAdminProperty($property) {
        $session = self::getSessionNamespace("admin.property");
        if (!self::isAdminPropertyAvailableInURL($property)) {
            return;
        }
        $session[$property] = self::getAdminPropertyValueFromURL($property);
    }

	private static function getSessionNamespace($namespace) {
		return self::$container->getByType(\Nette\Http\Session::class)->getSection($namespace);
	}

	public static function cleanPasswordResetTokens() {
		self::teams()->update(['reset_code' => null])->where("[reset_code] IS NOT NULL AND updated < DATE_SUB(NOW(),INTERVAL 2 DAY)")->execute();
	}

}
