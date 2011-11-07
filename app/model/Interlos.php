<?php
class Interlos {

	private static $connection;

	private static $currentYear;

	private static $loggedTeam;

	private static $models = array();

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

	/** @return DibiConnection */
	public static function getConnection() {
		if (empty(self::$connection)) {
			return dibi::getConnection();
		}
		else {
			return self::$connection;
		}
	}

	/** @return DibiRow */
	public static function getCurrentYear() {
		if (!isset(self::$currentYear)) {
			self::$currentYear = self::years()->findCurrent();
		}
		return self::$currentYear;
	}

	/** @return DibiRow */
	public static function getLoggedTeam() {
		if (!isset(self::$loggedTeam)) {
			if (Environment::getUser()->isLoggedIn()) {
				self::$loggedTeam = Interlos::teams()->find(Environment::getUser()->getIdentity()->id_team);
			}
			else {
				self::$loggedTeam = NULL;
			}
		}
		return self::$loggedTeam;
	}

    public static function isAdminAccess() {
        return isset($_GET["admin-key"]) && Environment::getConfig("admin.key", NULL) == $_GET["admin-key"];
    }
    
    public static function isCronAccess() {
        return isset($_GET["admin-key"]) && Environment::getConfig("cron.key", NULL) == $_GET["cron-key"];
    }
    
    public static function isGameActive() {
        return self::isGameStarted() && !self::isGameEnd();
    }
    
    public static function isGameEnd() {
        return (time() > strtotime(Interlos::getCurrentYear()->game_end) || (self::isAdminAccess() && isset($_GET["game-end"])));
    }
    
    public static function isGameStarted() {
        return (strtotime(Interlos::getCurrentYear()->game_start) < time() || (self::isAdminAccess() && isset($_GET["game-started"])));
    }
    
    public static function isRegistrationActive() {
        return self::isGameStarted() && !self::isRegistrationEnd();
    }

    public static function isRegistrationEnd() {
        return (strtotime(Interlos::getCurrentYear()->registration_end) < time() || (self::isAdminAccess() && isset($_GET["registration-end"])));
    }
    
    public static function isRegistrationStarted() {
        return (strtotime(Interlos::getCurrentYear()->registration_start) < time() || (self::isAdminAccess() && isset($_GET["registration-started"])));
    }
    
	public static function resetTemporaryTables() {
        if (!self::isCronAccess() || !isset($_GET["update-database"])) {
            return;
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
	}

	/** @return SchoolsModel */
	public static function schools() {
		return self::getModel("schools");
	}

	/** @return ScoreModel */
	public static function score() {
		return self::getModel("score");
	}

	public static function setConnection(DibiConnection $connection) {
		self::$connection = $connection;
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
			throw  new InvalidStateException("The class [$className] does not exists.");
		}
		// Check whether the class is really the model class
		$key = ExtraArray::keysOf(class_implements($className), "InterlosModel");
		if (empty($key)) {
			throw new InvalidStateException("The class [$className] does not implement interface [InterlosModel]");
		}
		// Return new instance of model class
		return new $className(self::getConnection());
	}

	private static function getModel($name) {
		if (empty(self::$models[$name])) {
			self::$models[$name] = self::createModel($name);
		}
		return self::$models[$name];
	}

}
