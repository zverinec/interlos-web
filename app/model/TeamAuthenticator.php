<?php
/**
 * @author Jan Papousek
 */
class TeamAuthenticator implements Nette\Security\IAuthenticator
{

	const TEAM = "team";

	public function authenticate(array $credentials) {
		$name		= $credentials[Nette\Security\IAuthenticator::USERNAME];
		$password	= self::passwordHash($credentials[Nette\Security\IAuthenticator::PASSWORD]);
		$row = Interlos::teams()->findAll()->where("[name] = %s", $name)->fetch();
		if (empty($row)) {
			throw new Nette\Security\AuthenticationException(
				"Tým '$name' neexistuje.",
				Nette\Security\IAuthenticator::IDENTITY_NOT_FOUND
			);
		}
		if ($row["password"] != $password) {
			throw new Nette\Security\AuthenticationException(
				"Heslo se neshoduje.",
				Nette\Security\IAuthenticator::INVALID_CREDENTIAL
			);
		}
		return new Nette\Security\Identity($name, self::TEAM, array("id_team" => $row["id_team"], "role" => self::TEAM));
	}

	public static function passwordHash($password) {
		return sha1($password);
	}

}
