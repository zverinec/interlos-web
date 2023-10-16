<?php

use Nette\Security\IIdentity;
use Nette\Security\Authenticator;

/**
 * @author Jan Papousek
 */
class TeamAuthenticator implements Authenticator
{

	const TEAM = "team";

	public function authenticate(string $user, string $password): IIdentity {
		$name		= $user;
		$password	= self::passwordHash($password);
		$row = Interlos::teams()->findAll()->where("[name] = %s", $name)->fetch();
		if (empty($row)) {
			throw new Nette\Security\AuthenticationException(
				"Tým '$name' neexistuje.",
				Authenticator::IDENTITY_NOT_FOUND
			);
		}
		if ($row["password"] != $password) {
			throw new Nette\Security\AuthenticationException(
				"Heslo se neshoduje.",
				Authenticator::INVALID_CREDENTIAL
			);
		}
		return new Nette\Security\SimpleIdentity($name, self::TEAM, array("id_team" => $row["id_team"], "role" => self::TEAM));
	}

	public static function passwordHash($password) {
		return sha1($password);
	}

}
