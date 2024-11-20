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
        $email        = $user;
        $password    = self::passwordHash($password);
        $row = Interlos::teams()->findAll()->where("[email] = %s", $email)->fetch();
        if (empty($row)) {
            throw new Nette\Security\AuthenticationException(
                "Pod e-mailem '$email' není nikdo zaregistrovaný.",
                Authenticator::IDENTITY_NOT_FOUND
            );
        }
        if ($row["password"] != $password) {
            throw new Nette\Security\AuthenticationException(
                "Heslo se neshoduje.",
                Authenticator::INVALID_CREDENTIAL
            );
        }
        return new Nette\Security\SimpleIdentity($row["name"], self::TEAM, array("id_team" => $row["id_team"], "role" => self::TEAM));
    }

    public static function passwordHash($password) {
        return sha1($password);
    }

}
